<?php

namespace App\Http\Controllers;

use App\Models\SessionDepartment;
use App\Http\Requests\StoreSessionDepartmentRequest;
use App\Http\Requests\UpdateSessionDepartmentRequest;
use App\Models\Department;
use App\Models\DepartmentCouncil;
use App\Models\SessionDepartmentAttendance;
use App\Models\SessionDepartmentTopic;
use App\Models\SessionDepartmentUser;
use App\Models\TopicAgenda;
use Carbon\Carbon;
use DateTime;
use Dotenv\Exception\ValidationException;
// use Illuminate\Http\Client\Request;
use Illuminate\Http\Request;

class SessionDepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        // $this->middleware('is_super_or_system_admin')->except('index', 'show', 'getFacultiesByHeadquarter');
        $this->middleware('ajax_only')->except('index', 'create', 'edit', 'show', 'startSession');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = SessionDepartment::paginate(10);
        $data = [
            'sessions' => $sessions
        ];
        return view('sessions.department.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // if user position is secretary of department council
        if (auth()->user()->position_id == 2) {
            $secretaryDepartments = DepartmentCouncil::where('department_councils.user_id', auth()->id())
                ->join('departments', 'departments.id', '=', 'department_councils.department_id')
                ->select('departments.id as department_id', 'departments.ar_name as department_name');

            // array like [department_id => department_name]
            $departments = array_combine($secretaryDepartments->pluck('department_id')->toArray(), $secretaryDepartments->pluck('department_name')->toArray());

            $agendas = TopicAgenda::get();

            $data = [
                'agendas' => $agendas,
                'departments' => $departments
            ];
            return view('sessions.department.create', compact('data'));
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionDepartmentRequest $request)
    {
        try {
            if (auth()->user()->position_id == 2) {

                // dd($request->all());

                $departmentCode = Department::where('id', $request->department_id)->value('code');
                // let code contain departmentCode + / + random of 3 digit number
                $code = $departmentCode . '/' . rand(100, 999);

                $latestRecord = SessionDepartment::latest('id')->first();

                $latestOrder = intval($latestRecord->order ?? '0');
                $newOrder = $latestOrder + 1;

                $startDate = Carbon::parse($request->start_time);
                // Format the start date and end date to match the desired format
                $start_time = $startDate->format('Y-m-d H:i');
                // Cast total_hours to an integer
                $totalhours = intval($request->total_hours);
                // Add the total hours to the start date
                $endDateCarbon = $startDate->addHours($totalhours);
                $schedual_end_time = $endDateCarbon->format('Y-m-d H:i');
                $created_by = auth()->id();
                $responsible_id = DepartmentCouncil::where('department_id', $request->department_id)
                    ->where('position_id', 3)
                    ->value('user_id');

                $session = SessionDepartment::create([
                    'department_id' => $request->department_id,
                    'total_hours' => $request->total_hours,
                    'decision_by' => $request->decision_by,
                    'place' => $request->place,
                    'start_time' => $start_time,
                    'schedual_end_time' => $schedual_end_time,
                    'created_by' => $created_by,
                    'responsible_id' => $responsible_id,
                    'code' => $code,
                    'order' => $newOrder,
                    'status' => 0, // pending
                ]);

                foreach ($request->agenda_id as $agendaId) {
                    $sessionTopics[] = [
                        'session_id' => $session->id,
                        'agenda_id' => $agendaId
                    ];
                }
                // insert agendas
                SessionDepartmentTopic::insert($sessionTopics);

                foreach ($request->user_id as $userId) {
                    $sessionUsers[] = [
                        'session_id' => $session->id,
                        'user_id' => $userId
                    ];
                }
                // insert users
                SessionDepartmentUser::insert($sessionUsers);

                return response()->json(['message' => 'Session created successfully']);
            }
        } catch (ValidationException $e) {
            dd($e);
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($session_id)
    {
        $session = SessionDepartment::findOrFail($session_id);

        $sessionUsers = SessionDepartmentUser::where('session_department_user.session_id', $session_id)
            ->join('users', 'users.id', '=', 'session_department_user.user_id')
            ->select('users.name as user_name');

        $sessionTopics = SessionDepartmentTopic::where('session_department_topics.session_id', $session_id)
            ->join('topic_agendas', 'topic_agendas.id', '=', 'session_department_topics.agenda_id')
            ->join('topics', 'topics.id', '=', 'topic_agendas.topic_id')
            ->select('topics.title as topic_title');

        $data = [
            'session' => $session,
            'invitations' => $sessionUsers->pluck('user_name'),
            'topics' => $sessionTopics->pluck('topic_title')
        ];

        return view('sessions.department.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($session_id)
    {
        // if user position is secretary of department council
        if (auth()->user()->position_id == 2) {
            $session = SessionDepartment::findOrFail($session_id);
            $sessionTopics = SessionDepartmentTopic::where('session_id', $session_id)->pluck('agenda_id');
            $sessionUsers = SessionDepartmentUser::where('session_id', $session_id)->pluck('user_id');

            // can't edit if status accepted or rejected
            if ($session->status != 1 || $session->status != 2) {

                $secretaryDepartments = DepartmentCouncil::where('department_councils.user_id', auth()->id())
                    ->join('departments', 'departments.id', '=', 'department_councils.department_id')
                    ->select('departments.id as department_id', 'departments.ar_name as department_name');

                // array like [department_id => department_name]
                $departments = array_combine($secretaryDepartments->pluck('department_id')->toArray(), $secretaryDepartments->pluck('department_name')->toArray());

                $data = [
                    'session' => $session,
                    'departments' => $departments,
                    'sessionUsers' => $sessionUsers,
                    'sessionTopics' => $sessionTopics
                ];

                return view('sessions.department.edit', compact('data'));
            } else {
                abort(403);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionDepartmentRequest $request, $session_id)
    {
        // dd($request->all());
        try {
            if (auth()->user()->position_id == 2) {

                $session = SessionDepartment::findOrFail($session_id);
                // delete old records
                SessionDepartmentTopic::where('session_id', $session_id)->delete();
                SessionDepartmentUser::where('session_id', $session_id)->delete();


                $departmentCode = Department::where('id', $request->department_id)->value('code');
                $sessionCode = $session->code;
                // Split the $sessionCode at the "/"
                list($beforeSlash, $afterSlash) = explode('/', $sessionCode);
                // Replace the part before "/" with $departmentCode
                $code = $departmentCode . '/' . $afterSlash;


                $startDate = Carbon::parse($request->start_time);
                // Format the start date and end date to match the desired format
                $start_time = $startDate->format('Y-m-d H:i');
                // Cast total_hours to an integer
                $totalhours = intval($request->total_hours);
                // Add the total hours to the start date
                $endDateCarbon = $startDate->addHours($totalhours);
                $schedual_end_time = $endDateCarbon->format('Y-m-d H:i');


                $created_by = auth()->id();
                $responsible_id = DepartmentCouncil::where('department_id', $request->department_id)
                    ->where('position_id', 3)
                    ->value('user_id');


                $session->update([
                    'department_id' => $request->department_id,
                    'total_hours' => $request->total_hours,
                    'decision_by' => $request->decision_by,
                    'place' => $request->place,
                    'start_time' => $start_time,
                    'schedual_end_time' => $schedual_end_time,
                    'created_by' => $created_by,
                    'responsible_id' => $responsible_id,
                    'code' => $code,
                ]);


                foreach ($request->agenda_id as $agendaId) {
                    $sessionTopics[] = [
                        'session_id' => $session->id,
                        'agenda_id' => $agendaId
                    ];
                }
                // insert agendas
                SessionDepartmentTopic::insert($sessionTopics);


                foreach ($request->user_id as $userId) {
                    $sessionUsers[] = [
                        'session_id' => $session->id,
                        'user_id' => $userId
                    ];
                }
                // insert users
                SessionDepartmentUser::insert($sessionUsers);

                return response()->json(['message' => 'Session updated successfully']);
            }
        } catch (ValidationException $e) {
            dd($e);
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($session_id)
    {
        try {
            $session = SessionDepartment::findOrFail($session_id);
            $session->delete();

            return response()->json(['message' => 'Session deleted successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getInvitationFromDepartmentId($department_id)
    {
        $usersData = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->join('users', 'users.id', '=', 'department_councils.user_id')
            ->select('users.id as user_id', 'users.name as user_name');

        // array like [user_id => user_name]
        $invitations = array_combine($usersData->pluck('user_id')->toArray(), $usersData->pluck('user_name')->toArray());

        return response()->json($invitations);
    }
    public function changeStatus($session_id, Request $request)
    {
        $session = SessionDepartment::findOrFail($session_id);

        $session->update([
            'status' => $request->status,
            'reject_reason' => $request->reject_reason,
        ]);

        return response()->json([
            'message' => 'Status changed successfully',
            'data' => ['status' => $session->status, 'reject_reason' => $session->reject_reason]
        ], 200);
    }

    public function startSession($session_id)
    {
        $session = SessionDepartment::findOrFail($session_id);
        $sessionTopics = SessionDepartmentTopic::where('session_id', $session_id)->pluck('agenda_id');
        $sessionUsers = SessionDepartmentUser::where('session_id', $session_id)->pluck('user_id');

        $data = [
            'session' => $session,
            'sessionTopics' => $sessionTopics,
            'sessionUsers' => $sessionUsers,
        ];

        return view('sessions.department.start_session', compact('data'));
    }

    public function fetchAttendance($session_id)
    {
        $session = SessionDepartment::findOrFail($session_id);
        $sessionUsers = SessionDepartmentUser::where('session_department_user.session_id', $session_id)
            ->join('users', 'users.id', 'session_department_user.user_id')
            ->select('users.name as user_name', 'users.id as user_id')
            ->get();

        $sessionAttendance = SessionDepartmentAttendance::where('session_id', $session_id)->get();

        // Create an associative array for attendance with user_id as key
        $attendanceData = $sessionAttendance->mapWithKeys(function ($item) {
            return [$item->user_id => $item->status];
        });

        $invitations = $sessionUsers->pluck('user_name', 'user_id')->toArray();

        $data = [
            'session' => $session,
            'invitations' => $invitations,
            'attendance' => $attendanceData
        ];

        return view('sessions.department.attendance', compact('data'));
    }


    public function saveAttendance(Request $request, $session_id)
    {
        // delete attendance if found
        SessionDepartmentAttendance::where('session_id',$session_id)->delete();

        if (is_array($request->input('attendance'))) {
            foreach ($request->input('attendance') as $record) {
                $attendance[] = [
                    'session_id' => $session_id,
                    'user_id' => $record['user_id'],
                    'status' => $record['status']
                ];
            }
        }

        SessionDepartmentAttendance::insert($attendance);

        return response()->json(['message' => 'Attendance saved successfully'], 200);
    }

}

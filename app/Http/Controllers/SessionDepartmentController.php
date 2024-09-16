<?php

namespace App\Http\Controllers;

use App\Models\SessionDepartment;
use App\Http\Requests\StoreSessionDepartmentRequest;
use App\Http\Requests\UpdateSessionDepartmentRequest;
use App\Models\Department;
use App\Models\DepartmentCouncil;
use App\Models\SessionDepartmentTopic;
use App\Models\SessionDepartmentUser;
use App\Models\TopicAgenda;
use Carbon\Carbon;
use DateTime;
use Dotenv\Exception\ValidationException;

class SessionDepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        // $this->middleware('is_super_or_system_admin')->except('index', 'show', 'getFacultiesByHeadquarter');
        // $this->middleware('ajax_only')->except('index');
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

                return response()->json(['message' => 'session created successfully']);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($session_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionDepartmentRequest $request, $session_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($session_id)
    {
        //
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
}

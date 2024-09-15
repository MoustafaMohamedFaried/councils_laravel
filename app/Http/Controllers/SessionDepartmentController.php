<?php

namespace App\Http\Controllers;

use App\Models\SessionDepartment;
use App\Http\Requests\StoreSessionDepartmentRequest;
use App\Http\Requests\UpdateSessionDepartmentRequest;
use App\Models\DepartmentCouncil;
use App\Models\TopicAgenda;
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
            if (auth()->user()->position_id == 3) {
                dd($request->all());
            }
        } catch (ValidationException $e) {
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

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\DepartmentCouncil;
use App\Models\Faculty;
use App\Models\Position;
use App\Models\User;
use Dotenv\Exception\ValidationException;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getDepartmentsByFaculty');
        $this->middleware('is_active')->except('getDepartmentsByFaculty');
        $this->middleware('is_super_or_system_admin')->except('index', 'show', 'getDepartmentsByFaculty');
        $this->middleware('ajax_only')->except('index', 'edit');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::paginate(10);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($faculty_id = null)
    {
        $faculties = Faculty::get();
        return view('departments.create', compact('faculties','faculty_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        try {
            // let code contain dept_ + random of 3 digit number
            $code = 'dept_' . rand(100, 999);

            Department::create([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'faculty_id' => $request->faculty_id,
                'code' => $code,
            ]);

            $departmentData = Department::latest('id')->with('faculty')->first()->toArray();

            return response()->json(['message' => 'Department saved successfully', 'data' => $departmentData], 200);
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
    public function show($department_id)
    {
        $department = Department::findOrFail($department_id);

        return view('departments.view', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($department_id)
    {
        $department = Department::findOrFail($department_id);
        $faculties = Faculty::whereNot('id', $department->faculty_id)->get();

        $headOfDepartment = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 3)
            ->join('users', 'users.id', '=', 'department_councils.user_id')
            ->select('users.name as user_name');

        $secertaryOfDepartmentCouncil = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 2)
            ->join('users', 'users.id', '=', 'department_councils.user_id')
            ->select('users.name as user_name');

        $memebers = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 1)
            ->join('users', 'users.id', '=', 'department_councils.user_id')
            ->select('users.name as user_name');
        $data = [
            'department' => $department,
            'faculties' => $faculties,
            'departmentCouncil' => [
                'رئيس القسم' => $headOfDepartment->value('user_name'),
                'امين مجلس القسم' => $secertaryOfDepartmentCouncil->value('user_name'),
                'الاعضاء' => $memebers->value('user_name'),
            ],
        ];
        return view('departments.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, $department_id)
    {
        try {
            $department = Department::findOrFail($department_id);

            $department->update([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'faculty_id' => $request->faculty_id,
            ]);

            $faculty = Faculty::where('id', $department->faculty_id)->first()->toArray();

            return response()->json([
                'message' => 'Department updated successfully',
                'data' => ['department' => $department, 'faculty' => $faculty]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($department_id)
    {
        try {
            $department = Department::findOrFail($department_id);
            $department->delete();

            return response()->json(['message' => 'Department deleted successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getDepartmentsByFaculty($faculty_id)
    {
        // Fetch departments related to the selected faculty
        $departments = Department::where('faculty_id', $faculty_id)->get();

        // Return the departments as JSON
        return response()->json($departments);
    }
    public function getDepartment($department_id)
    {
        $department = Department::findOrFail($department_id);

        // Return the department as JSON
        return response()->json($department);
    }

}

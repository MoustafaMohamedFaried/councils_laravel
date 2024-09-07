<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentCouncilRequest;
use App\Models\Department;
use App\Models\DepartmentCouncil;
use App\Models\Faculty;
use App\Models\Position;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentCouncilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        $this->middleware('is_super_admin');
        $this->middleware('ajax_only');
    }
    public function getForm($department_id)
    {
        $facultyId = Department::where('id', $department_id)->value('faculty_id');

        $selectedHeadOfDepartment = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 3) // Specify the table for position_id
            ->join('users', 'department_councils.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.id as user_id')
            ->get();
        $headOfDepartment = User::where('faculty_id', $facultyId)
            ->where('position_id', 3) // head of department
            ->whereNotIn('id', $selectedHeadOfDepartment->pluck('user_id'))
            ->pluck('name', 'id');


        $selectedSecretaryOfDepartmentCouncil = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 2) // secretary of department council
            ->join('users', 'department_councils.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.id as user_id')
            ->get();
        $secretaryOfDepartmentCouncil = User::where('faculty_id', $facultyId)
            ->where('position_id', 2) // secretary of department council
            ->whereNotIn('id', $selectedSecretaryOfDepartmentCouncil->pluck('user_id'))
            ->pluck('name', 'id');


        $selectedCouncilMembers = DepartmentCouncil::where('department_councils.department_id', $department_id)
            ->where('department_councils.position_id', 1) // acadimic staff
            ->join('users', 'department_councils.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.id as user_id')
            ->get();
        $members = User::where('faculty_id', $facultyId)
            ->where('position_id', 1) // acadimic staff
            ->whereNotIn('id', $selectedCouncilMembers->pluck('user_id'))
            ->pluck('name', 'id');

        $data = [
            'headOfDepartment' => $headOfDepartment,
            'secretaryOfDepartmentCouncil' => $secretaryOfDepartmentCouncil,
            'members' => $members,
            'department_id' => $department_id,
            'selectedHeadOfDepartment' => $selectedHeadOfDepartment,
            'selectedSecretaryOfDepartmentCouncil' => $selectedSecretaryOfDepartmentCouncil,
            'selectedCouncilMembers' => $selectedCouncilMembers,
        ];

        return view("departments.council", compact('data'));
    }

    public function formateCouncil(DepartmentCouncilRequest $request, $department_id)
    {
        try {
            DB::table('department_councils')->where('department_id', $department_id)->delete();
            // Prepare the data
            $councilMembers = [
                [
                    'user_id' => $request->head_of_department,
                    'position_id' => 3, // head of department position
                    'department_id' => $department_id,
                ],
                [
                    'user_id' => $request->secretary_of_department_council,
                    'position_id' => 2, // secretary_of_department_council position
                    'department_id' => $department_id,
                ],
                [
                    'user_id' => $request->members,
                    'position_id' => 1, // member position
                    'department_id' => $department_id,
                ],
            ];

            // Insert or update council members
            foreach ($councilMembers as $memberData) {
                DepartmentCouncil::updateOrCreate([
                    'user_id' => $memberData['user_id'],
                    'department_id' => $department_id,
                    'position_id' => $memberData['position_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $formatedDepartmentCouncil = DepartmentCouncil::where('department_id', $department_id)->get();

            $DepartmentCouncil = $formatedDepartmentCouncil->map(function ($council) {
                // Get the user name and position name from the related tables
                $userName = User::where('id', $council->user_id)->value('name');
                $position = Position::where('id', $council->position_id)->value('ar_name');

                // Return an array for each council item
                return [
                    'user_name' => $userName,
                    'position' => $position
                ];
            })->toArray();


            return response()->json(['message' => "Council formatted successfully", 'data' => $DepartmentCouncil], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}

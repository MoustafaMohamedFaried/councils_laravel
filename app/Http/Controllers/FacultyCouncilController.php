<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacultyCouncilRequest;
use App\Models\Faculty;
use App\Models\FacultyCouncil;
use App\Models\Position;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyCouncilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        $this->middleware('is_super_or_system_admin');
        $this->middleware('ajax_only');
    }
    public function getForm($faculty_id)
    {
        $deanOfCollege = User::where('faculty_id', $faculty_id)
            ->where('position_id', 5)
            ->select(['name', 'id']);

        $secretaryOfCollegeCouncil = User::where('faculty_id', $faculty_id)
            ->where('position_id', 4)
            ->select(['name', 'id']);

        $councilMembersIds = FacultyCouncil::where('faculty_id', $faculty_id)
            ->whereNotIn('position_id', [4, 5])
            ->pluck('user_id')
            ->toArray();
        $selectedCouncilMembers = User::whereIn('id', $councilMembersIds)->get(['id', 'name']);

        $members = User::where('faculty_id', $faculty_id)
            ->whereNotIn('position_id', [4, 5])
            ->whereNotIn('id', $councilMembersIds)
            ->get();

        $data = [
            'deanOfCollege' => $deanOfCollege,
            'secretaryOfCollegeCouncil' => $secretaryOfCollegeCouncil,
            'members' => $members,
            'faculty_id' => $faculty_id,
            'selectedCouncilMembers' => $selectedCouncilMembers,
        ];

        return view("facullties.council", compact('data'));
    }

    public function formateCouncil(FacultyCouncilRequest $request, $faculty_id)
    {
        try {
            DB::table('faculty_councils')->where('faculty_id', $faculty_id)->delete();
            // Prepare the data
            $councilMembers = [
                [
                    'user_id' => $request->dean_of_college,
                    'position_id' => 5, // dean of college position
                    'faculty_id' => $faculty_id,
                ],
                [
                    'user_id' => $request->secretary_of_college_council,
                    'position_id' => 4, // secretary_of_college_council position
                    'faculty_id' => $faculty_id,
                ],
                [
                    'user_id' => $request->members,
                    'position_id' => 1, // member position
                    'faculty_id' => $faculty_id,
                ],
            ];

            // Insert or update council members
            foreach ($councilMembers as $memberData) {
                FacultyCouncil::updateOrCreate([
                    'user_id' => $memberData['user_id'],
                    'faculty_id' => $faculty_id,
                    'position_id' => $memberData['position_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $formatedFacultyCouncil = FacultyCouncil::where('faculty_id', $faculty_id)->get();

            $facultyCouncil = $formatedFacultyCouncil->map(function ($council) {
                // Get the user name and position name from the related tables
                $userName = User::where('id', $council->user_id)->value('name');
                $position = Position::where('id', $council->position_id)->value('ar_name');

                // Return an array for each council item
                return [
                    'user_name' => $userName,
                    'position' => $position
                ];
            })->toArray();


            return response()->json(['message' => "Council formatted successfully", 'data' => $facultyCouncil], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}

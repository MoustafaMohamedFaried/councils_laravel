<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Models\Department;
use App\Models\FacultyCouncil;
use App\Models\FacultyHeadquarter;
use App\Models\Headquarter;
use App\Models\Position;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getFacultiesByHeadquarter');
        $this->middleware('is_active')->except('getFacultiesByHeadquarter');
        $this->middleware('is_super_or_system_admin')->except('index','show','getFacultiesByHeadquarter');
        $this->middleware('ajax_only')->except('index','edit');
    }

    public function index()
    {
        $faculties = Faculty::paginate(10);
        return view('facullties.index', compact('faculties'));
    }

    public function create()
    {
        $headquarters = Headquarter::get();
        return view('facullties.create', compact('headquarters'));
    }

    public function store(StoreFacultyRequest $request)
    {
        try {
            // Get the latest code from the database
            $latestCode = Faculty::latest('id')->first()->code ?? 'fa_0';

            // Extract the number part from the latest code
            $latestNumber = intval(preg_replace('/[^0-9]+/', '', $latestCode));

            // Increment the number
            $newNumber = $latestNumber + 1;

            // Generate the new code
            $newCode = 'fa_' . $newNumber;

            Faculty::create([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'headquarter_id' => $request->headquarter_id,
                'code' => $newCode,
            ]);

            $facultyData = Faculty::latest('id')->first()->toArray();

            return response()->json(['message' => 'Faculty saved successfully', 'data' => $facultyData], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show($faculty_id)
    {
        $faculty = Faculty::findOrFail($faculty_id);
        return view("facullties.view", compact('faculty'));
    }

    public function edit($faculty_id)
    {
        $faculty = Faculty::findOrFail($faculty_id);
        $facultyDepartments = Department::where('faculty_id', $faculty_id)->paginate(5);
        // pass headquarters except the faculty's headquarter
        $headquarters = Headquarter::whereNot('id', $faculty->headquarter_id)->get();

        $facultyCouncilUserIds = FacultyCouncil::where('faculty_id', $faculty_id)->pluck('user_id')->toArray();
        $facultyCouncilUserPositionIds = FacultyCouncil::where('faculty_id', $faculty_id)->pluck('position_id')->toArray();

        $facultyCouncilUsers = User::whereIn('id', $facultyCouncilUserIds)->pluck('name')->toArray();
        $facultyCouncilUserPositions = Position::whereIn('id', $facultyCouncilUserPositionIds)->pluck('ar_name')->toArray();

        $facultyCouncil = array_combine($facultyCouncilUsers, $facultyCouncilUserPositions);

        $data = [
            'faculty' => $faculty,
            'facultyDepartments' => $facultyDepartments,
            'headquarters' => $headquarters,
            'facultyCouncil' => $facultyCouncil,
        ];
        // dd($data);
        return view('facullties.edit', compact('data'));
    }

    public function update(UpdateFacultyRequest $request, $faculty_id)
    {
        try {
            // dd($request->all());
            $faculty = Faculty::findOrFail($faculty_id);
            $faculty->update([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'headquarter_id' => $request->headquarter_id,
            ]);

            return response()->json(['message' => 'Faculty updated successfully', 'data' => $faculty], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function destroy($faculty_id)
    {
        try {
            $faculty = Faculty::findOrFail($faculty_id);
            $faculty->delete();

            return response()->json(['message' => 'Faculty deleted successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getFacultiesByHeadquarter($headquarter_id)
    {
        // Fetch faculties related to the selected headquarter
        $faculties = Faculty::where('headquarter_id', $headquarter_id)->get();

        // Return the faculties as JSON
        return response()->json($faculties);
    }
}

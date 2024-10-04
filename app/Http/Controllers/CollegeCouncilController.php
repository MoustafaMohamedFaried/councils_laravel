<?php

namespace App\Http\Controllers;

use App\Models\CollegeCouncil;
use App\Models\SessionDepartment;
use App\Models\SessionDepartmentTopic;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class CollegeCouncilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        // $this->middleware('is_super_or_system_admin')->except('index', 'show', 'getFacultiesByHeadquarter');
        $this->middleware('ajax_only')->except('index', 'edit');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collegeCouncils = CollegeCouncil::whereNull('agenda_id')->paginate(10);

        $data = [
            'collegeCouncils' => $collegeCouncils
        ];

        return view('sessions.college_council.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sessions = SessionDepartment::get();

        $data = [
            'sessions' => $sessions
        ];

        return view('sessions.college_council.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            // Get the session topics for the provided session ID
            $sessionTopics = SessionDepartmentTopic::where('session_id', $request->session_id)->pluck('agenda_id');

            // Create one CollegeCouncil record with agenda_id as null
            $collegeCouncil = CollegeCouncil::create([
                'session_id' => $request->session_id,
                'agenda_id' => null,
                'status' => 0, // pending
            ]);

            // Create additional CollegeCouncil records with agenda_id from session topics
            foreach ($sessionTopics as $topic) {
                $insertWithTopics[] = [
                    'session_id' => $request->session_id,
                    'agenda_id' => $topic,
                    'status' => 0, // pending
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            CollegeCouncil::insert($insertWithTopics);

            $record = CollegeCouncil::with(['session.responsible'])->findOrFail($collegeCouncil->id);

            return response()->json([
                'message' => "Session report uploaded successfully",
                'data' => $record,
            ], 200);
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
        $collegeCouncil = CollegeCouncil::where('session_id', $session_id)
            ->whereNotNull('agenda_id')
            ->get();

        // dd($collegeCouncil->toArray());

        $data = [
            'session' => $session,
            'collegeCouncil' => $collegeCouncil
        ];

        return view("sessions.college_council.view", compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($collegeCouncil_id)
    {
        $collegeCouncil = CollegeCouncil::findOrFail($collegeCouncil_id);

        $collegeCouncilWithTopics = CollegeCouncil::where('session_id', $collegeCouncil->session_id)
            ->whereNotNull('agenda_id')
            // ->with('agenda')
            ->get();

        // dd($collegeCouncilWithTopics->toArray(), $collegeCouncil->toArray());

        $data = [
            'collegeCouncil' => $collegeCouncil,
            'collegeCouncilWithTopics' => $collegeCouncilWithTopics
        ];

        return view('sessions.college_council.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $collegeCouncil_id)
    {
        try {
            if ($request->changeSingleStatus) {
                dd("Change Single");
            } else {
                // dd($request->all());
                CollegeCouncil::where('session_id', $request->session_id)
                    ->update([
                        'status' => $request->changeStatusTotal,
                        'reject_reason' => $request->rejectReasonTotal
                    ]);
            }

            return response()->json(['message' => 'Decision has been taken successfully'],200);
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
    public function destroy($collegeCouncil_id)
    {
        //
    }
}

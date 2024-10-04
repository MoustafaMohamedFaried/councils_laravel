<?php

namespace App\Http\Controllers;

use App\Models\CollegeCouncil;
use App\Models\SessionDepartment;
use App\Models\SessionDepartmentTopic;
use Illuminate\Http\Request;

class CollegeCouncilController extends Controller
{
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


        return response()->json([
            'message' => "Session report uploaded successfully",
            'data' => CollegeCouncil::with(['session.responsible'])->findOrFail($collegeCouncil->id),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CollegeCouncil $collegeCouncil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollegeCouncil $collegeCouncil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollegeCouncil $collegeCouncil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollegeCouncil $collegeCouncil)
    {
        //
    }
}

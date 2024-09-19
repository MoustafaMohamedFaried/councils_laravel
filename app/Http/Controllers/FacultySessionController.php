<?php

namespace App\Http\Controllers;

use App\Models\FacultySession;
use App\Http\Requests\StoreFacultySessionRequest;
use App\Http\Requests\UpdateFacultySessionRequest;

class FacultySessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $sessions = FacultySession::paginate(10);
        // $data = [
        //     'sessions' => $sessions
        // ];
        return view('sessions.faculty.index');
        // return view('sessions.faculty.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultySessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FacultySession $facultySession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacultySession $facultySession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacultySessionRequest $request, FacultySession $facultySession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacultySession $facultySession)
    {
        //
    }
}

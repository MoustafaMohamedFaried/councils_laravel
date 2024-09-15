<?php

namespace App\Http\Controllers;

use App\Models\SessionDepartment;
use App\Http\Requests\StoreSessionDepartmentRequest;
use App\Http\Requests\UpdateSessionDepartmentRequest;

class SessionDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = SessionDepartment::paginate(10);
        $data = [
            'sessions' => $sessions
        ];
        return view('sessions.department.index');
        // return view('sessions.department.index',compact('data'));
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
    public function store(StoreSessionDepartmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionDepartment $sessionDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SessionDepartment $sessionDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionDepartmentRequest $request, SessionDepartment $sessionDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionDepartment $sessionDepartment)
    {
        //
    }
}

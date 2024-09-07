<?php

namespace App\Http\Controllers;

use App\Models\Headquarter;
use App\Http\Requests\StoreHeadquarterRequest;
use App\Http\Requests\UpdateHeadquarterRequest;
use App\Models\Faculty;
use Dotenv\Exception\ValidationException;

class HeadquarterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        $this->middleware('is_super_or_system_admin')->except('index','show');
        $this->middleware('ajax_only')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $headquarters = Headquarter::paginate(10);
        return view('headquarters.index', compact('headquarters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('headquarters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHeadquarterRequest $request)
    {
        try {
            // Get the latest code from the database
            $latestCode = Headquarter::latest('id')->first()->code ?? 'hq_0';

            // Extract the number part from the latest code
            $latestNumber = intval(preg_replace('/[^0-9]+/', '', $latestCode));

            // Increment the number
            $newNumber = $latestNumber + 1;

            // Generate the new code
            $newCode = 'hq_' . $newNumber;

            Headquarter::create([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'address' => $request->address,
                'code' => $newCode,
            ]);

            $headquarterData = Headquarter::latest('id')->first()->toArray();

            return response()->json(['message' => 'Headquarter saved successfully', 'data' => $headquarterData], 200);
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
    public function show($headquarter_id)
    {
        $headquarter = Headquarter::findOrFail($headquarter_id);
        // $relatedFaculties = Faculty::where('headquarter_id', $headquarter_id)->pluck('ar_name', 'en_name');
        // dd($relatedFaculties);
        return view('headquarters.view', compact('headquarter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($headquarter_id)
    {
        $headquarter = Headquarter::findOrFail($headquarter_id);
        return view('headquarters.edit', compact('headquarter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHeadquarterRequest $request, $headquarter_id)
    {
        try {
            $headquarter = Headquarter::findOrFail($headquarter_id);

            $headquarter->update([
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'address' => $request->address,
            ]);

            return response()->json(['message' => 'Headquarter updated successfully', 'data' => $headquarter], 200);
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
    public function destroy($headquarter_id)
    {
        try {
            $headquarter = Headquarter::findOrFail($headquarter_id);
            $headquarter->delete();

            return response()->json(['message' => 'Headquarter deleted successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}

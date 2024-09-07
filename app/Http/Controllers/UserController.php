<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Faculty;
use App\Models\Headquarter;
use App\Models\Position;
use App\Models\RegisterRequest;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        $this->middleware('is_super_or_system_admin')->except('registerRequestDecision', 'registerRequests');
        $this->middleware('ajax_only')->except('index', 'registerRequests');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::whereNot('name','Super Admin')->paginate(10);
        $users = User::paginate(10);
        return view("users.index", compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::get();
        $headquarters = Headquarter::get();
        $faculties = Faculty::get();
        $roles = Role::get();

        $data = [
            'positions' => $positions,
            'headquarters' => $headquarters,
            'faculties' => $faculties,
            'roles' => $roles,
        ];
        return view("users.create", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $insertUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'position_id' => $request->position_id,
                'password' => bcrypt($request->password),
                'faculty_id' => $request->faculty_id,
                'headquarter_id' => $request->headquarter_id,
                'is_active' => 1 // let user active
            ]);

            $userRole = Role::where('name', $request->role)->first();
            $insertUser->assignRole($userRole);  // Assign role to the created user

            $userData = User::latest('id')->first()->toArray();

            return response()->json(['message' => 'User saved successfully', 'data' => $userData], 200);
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
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return view("users.view", compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $positions = Position::whereNot('id', 'position_id')->get();
        $headquarters = Headquarter::whereNot('id', 'headquarter_id')->get();
        $faculties = Faculty::whereNot('id', 'faculty_id')->get();

        return view("users.edit", compact('positions', 'headquarters', 'faculties', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'position_id' => $request->position_id,
                'password' => bcrypt($request->password),
                'faculty_id' => $request->faculty_id,
                'headquarter_id' => $request->headquarter_id,
                'is_active' => 1 // let user active
            ]);

            return response()->json(['message' => 'User saved successfully', 'data' => $user], 200);
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
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function deactivateUser($user_id)
    {
        dd("iuaoda");
    }

    public function registerRequests()
    {
        if (auth()->user()->position_id == 3 || auth()->user()->hasRole('Super Admin')) {
            $requests = RegisterRequest::orderBy('created_at','desc')->paginate(10); // orderd Descending
            return view('users.register_requests', compact('requests'));
        } else {
            abort(401); // Unauthorized
        }
    }

    public function registerRequestDecision($user_id, Request $request)
    {
        if (auth()->user()->position_id == 3 || auth()->user()->hasRole('Super Admin')) {

            $user = User::findOrFail($user_id);
            $userRequest = RegisterRequest::where('user_id',$user_id);

            if ($request->decision == 1) {
                $user->update([
                    'is_active' => 1 // accept
                ]);
                $message = "User now is active";
            } else {
                $user->update([
                    'is_active' => 0 // reject
                ]);
                $message = "User now is not-active";
            }

            $userRequest->delete();

            return response()->json(['message' => $message], 200);
        } else {
            abort(401); // Unauthorized
        }
    }
}

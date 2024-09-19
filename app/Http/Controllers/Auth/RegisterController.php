<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Headquarter;
use App\Models\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'headquarter_id' => ['required'],
            'faculty_id' => ['required'],
            'department_id' => ['required'],
        ]);
    }

    public function showRegistrationForm()
    {
        $headquarters = Headquarter::get();
        $faculties = Faculty::get();
        $departments = Department::get();

        $data = [
            'headquarters' => $headquarters,
            'faculties' => $faculties,
            'departments' => $departments,
        ];

        return view('auth.register', compact('data'));
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'headquarter_id' => $data['headquarter_id'],
            'faculty_id' => $data['faculty_id'],
            'department_id' => $data['department_id'],
            'position_id' => 1, // acadimic staff
            'is_active' => 2 // pending to accept from head of department
        ]);

        // add user to table register requests
        RegisterRequest::create([
            'user_id' => $user->id,
            'department_id' => $data['department_id'],
        ]);

        $userRole = Role::where('name', 'Member')->first();
        $user->assignRole($userRole);  // Assign role to the created user

        return $user;
    }
}

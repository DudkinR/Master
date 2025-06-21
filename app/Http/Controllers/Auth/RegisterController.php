<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enterprise;
use App\Models\Department;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:15'], // Optional phone field
            'enterprise' => ['required', 'string', 'max:255'], // Enterprise name
            'department' => ['required', 'string', 'max:255'], // Department name
            'password' => ['required', 'string', 'min:8', 'confirmed'],            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Create or find the enterprise
        $enterprise = Enterprise::firstOrCreate(
            ['name' => $data['enterprise']],
            ['description' => 'Enterprise created during user registration']
        );
        // Create or find the department
        $department = Department::firstOrCreate(
            ['name' => $data['department']],
            ['description' => 'Department created during user registration']
        );
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null, // Optional phone field
            'password' => Hash::make($data['password']),
        ]);
        // Attach the user to the enterprise and department
        $user->enterprises()->attach($enterprise->id);
        $user->departments()->attach($department->id);
        $user->roles()->attach(7); // Assuming role ID 1 is the default user role
        return $user;
    }
}

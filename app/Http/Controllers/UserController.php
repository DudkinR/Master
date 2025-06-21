<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// model user
use App\Models\User;
// model role
use App\Models\Role;
// model department
use App\Models\Department;
// model enterprise
use App\Models\Enterprise;
use App\Filters\EnterpriseFilter;
use App\Filters\RoleFilter;
// DepartmentFilter
use App\Filters\DepartmentFilter;
use App\Filters\UserFilter;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
       // return $request->all();
       // Get all enterprises, departments, and roles
       // and users with their relationships
       
       $enterprises = Enterprise::all();
       $departments = Department::all();
       $roles = Role::all(); 
        // eager load users with roles, departments, and enterprises
        // and apply filters if any 
       $users = User::with(['roles', 'departments', 'enterprises']);
        //if request has search query, filter users
        if($request->input('search')!== null) {
            $users = (new UserFilter($request))->apply($users);
        }
        if(Auth::user()->roles->first()->name ==="Boss")
        {
            $users = (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))->handle($users);
        }
        elseif($request->has('enterprises')) {
            $users = (new EnterpriseFilter($request->enterprises))->handle($users);  
        }
        // no_enterprise

        if($request->has('roles')) {
            $users = (new RoleFilter($request->roles))->handle($users);
        }
        // no_role

        if($request->has('departments')) {
            $users = (new DepartmentFilter($request->departments))->handle($users);    
        }
        // no_department

        $users = $users->get(); 
     
        return view('users.index', compact('users' , 'enterprises', 'departments', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $enterprises = Enterprise::all();
        $departments = Department::all();
        $roles = Role::all();
        return view('users.create', compact('enterprises', 'departments', 'roles'));    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = bcrypt($request->input('password'));
        // Save user
        $user->save();
        // Attach roles
        if($request->has('roles')) {
            $user->roles()->attach($request->input('roles'));
        }
        // Attach departments
        if($request->has('departments')) {
            $user->departments()->attach($request->input('departments'));
        }
        // Attach enterprises
        if($request->has('enterprises')) {
            $user->enterprises()->attach($request->input('enterprises'));
        }
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        if($request->input('password')!== null) {         
            $user->password = bcrypt($request->input('password'));
        }
        // Update roles
        if($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        } else {
            $user->roles()->detach();
        }
        // Update departments
        if($request->has('departments')) {
            $user->departments()->sync($request->input('departments'));
        } else {
            $user->departments()->detach();
        }
        // Update enterprises
        if($request->has('enterprises')) {
            $user->enterprises()->sync($request->input('enterprises'));
        } else {
            $user->enterprises()->detach();
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

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
         if(Auth::user()->roles->first()->name ==="Engineer")
        {
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Master and Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master', 'Brigadir']);
            });
        }
         if(Auth::user()->roles->first()->name ==="Master")
        {
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->where('roles.name', ['Brigadir', 'Worker']);
            });
        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Worker
            ->whereHas('roles', function($query) {
                $query->where('roles.name', ['Worker']);
            });
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
        $user = User::findOrFail($id);
        // Detach roles, departments, and enterprises
        $user->roles()->detach();
        $user->departments()->detach();
        $user->enterprises()->detach();
        // Delete user
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    // Form_teams 
    public function Form_teams()
    {


        // Get users with their relationships
        $users = User::with(['roles', 'departments', 'enterprises' , 'children']);

        if(Auth::user()->roles->first()->name ==="Boss")
        {
            $users = (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))->handle($users);

        }
         if(Auth::user()->roles->first()->name ==="Engineer")
        {
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Master and Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master', 'Brigadir']);
            });
          
        }
         if(Auth::user()->roles->first()->name ==="Master")
        {
            
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->where('roles.name', ['Brigadir', 'Worker']);
            });
        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Worker
            ->whereHas('roles', function($query) {
                $query->where('roles.name', ['Worker']);
            });
           
        }
        $users = $users->get();
        $supervisors = $users->filter(fn($u) => $u->children->isNotEmpty());
        $unsupervised = $users->filter(fn($u) => $u->children->isEmpty());

        
        return view('users.teams', compact('users', 'supervisors', 'unsupervised'));
    }   
    // connect.users
    public function connectUsers(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $relatedUser = User::findOrFail($request->input('related_user_id'));

        // Attach the related user to the user
        $user->children()->attach($relatedUser->id);

        // Optionally, you can also attach the user to the related user's supervisors
        $relatedUser->supervisors()->attach($user->id);
        // json response

        return  response()->json([
            'success' => true,
            'message' => 'Users connected successfully.',
            'user' => $user->load('children', 'supervisors'), // Load relationships for the response
        ]);
    }
}

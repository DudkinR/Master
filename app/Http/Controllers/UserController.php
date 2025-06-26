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
                $query->whereIn('roles.name', ['Brigadir', 'Worker']);
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

        if(Auth::user()->roles->first()->name ==="Admin")
        {
           $users = User::with(['roles', 'departments', 'enterprises' , 'children'])->get();
           $users_id = $users->pluck('id');
              $bosses = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Boss']);
            })->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();
            $bosses_id = $bosses->pluck('id');
            $masters = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master']);
            })->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();
            $masters_id = $masters->pluck('id');
            $brigadiers = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Brigadir']);
            })->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();
            $brigadiers_id = $brigadiers->pluck('id');
            $workers = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Worker']);
            })->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();
            $workers_id = $workers->pluck('id');

        }
        if(Auth::user()->roles->first()->name ==="Boss")
        {
             $bosses = (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))->handle($users)
            // only Boss
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Boss']);
            });
             $masters=(new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master']);
            });
            $brigadiers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Brigadir']);
            });

            $workers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Worker']);
            });

        }
         if(Auth::user()->roles->first()->name ==="Engineer")
        {
             $bosses = (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))->handle($users)
             ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Boss']);
            });
             $masters=(new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master']);
            });
            $brigadiers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Brigadir']);
            });

            $workers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Worker']);
            });
          
        }
         if(Auth::user()->roles->first()->name ==="Master")
        {           
            $bosses =null;     
             
           $masters=User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Master']);
            })
            ->whereHas('departments', function($query) {
                $query->whereIn('department.id', [Auth::user()->departments->pluck('id')]);
            })
            ->whereHas('enterprises', function($query) {
                $query->whereIn('enterprise.id', [Auth::user()->enterprises->pluck('id')]);
            })
            ->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();  
            
            $brigadiers = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Brigadir']);
            })
                        ->whereHas('departments', function($query) {
                $query->whereIn('department.id', [Auth::user()->departments->pluck('id')]);
            })
            ->whereHas('enterprises', function($query) {
                $query->whereIn('enterprise.id', [Auth::user()->enterprises->pluck('id')]);
            })
            ->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();

            $workers = User::whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Worker']);
            }) ->whereHas('departments', function($query) {
                $query->whereIn('department.id', [Auth::user()->departments->pluck('id')]);
            })
            ->whereHas('enterprises', function($query) {
                $query->whereIn('enterprise.id', [Auth::user()->enterprises->pluck('id')]);
            })
            ->with(['roles', 'departments', 'enterprises' , 'children'])
            ->get();
            $users =User::whereHas('departments', function($query) {
                $query->whereIn('department.id', [Auth::user()->departments->pluck('id')]);
            })
            ->whereHas('enterprises', function($query) {
                $query->whereIn('enterprise.id', [Auth::user()->enterprises->pluck('id')]);
            })
            ->with(['roles', 'departments', 'enterprises' , 'children'])->get();
            

        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $bosses =null;   
            $masters =null; 
            $brigadiers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Brigadir']);
            })->get();
            $workers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users)
            // only Brigadir
            ->whereHas('roles', function($query) {
                $query->whereIn('roles.name', ['Worker']);
            })->get();
           
        }
  
        $supervisors = $users->filter(fn($u) => $u->children->isNotEmpty());
        $unsupervised = $users->filter(fn($u) => $u->children->isEmpty());
      
      //  return compact('users', 'supervisors', 'unsupervised', 'users_id', 'bosses_id', 'masters_id', 'brigadiers_id', 'workers_id');
        return view('users.teams', compact('users', 'users_id',  'bosses_id', 'masters_id', 'brigadiers_id', 'workers_id'));
    }
        // connect.users
    public function connectUsers(Request $request)
    {
       // return $request->all();
        
        $user = User::findOrFail($request->input('user_id'));
        $relatedUser = User::findOrFail($request->input('parent_id'));

        // Attach the related user to the user
        $user->children()->attach($relatedUser->id);

        return  response()->json([
            'success' => true,
            'message' => 'Users connected successfully.',
            'user' => $user->load('children', 'supervisors'), // Load relationships for the response
        ]);
    }
}

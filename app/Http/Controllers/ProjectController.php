<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Etalon;
use App\Models\User; // Assuming you have a User model for engineers
use Illuminate\Support\Facades\Auth; // For authentication


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(Auth::user()->roles->first()->name ==="Admin")
         {
             $engs = User::whereHas('roles', function ($query) {
                 $query->where('name', 'Engineer');
             })->get();
                $projects = Project::with(['enterprises', 'departments'])
                // order by id desc
                ->orderBy('id', 'desc')
                ->get();
                $etalons = Etalon::where('id_user', Auth::id())->get();
         }
         elseif(Auth::user()->roles->first()->name ==="Boss")
        {
            $enterpriseId = Auth::user()->enterprises->pluck('id');
            $departmentId = Auth::user()->departments->pluck('id');
            $engs = User::whereHas('roles', function ($query) {
                $query->where('name', 'Engineer');
            })
            ->where(function ($query) use ($enterpriseId, $departmentId) {
                $query->whereHas('enterprises', function ($q) use ($enterpriseId) {
                    $q->whereIn('enterprise.id', $enterpriseId);
                })->orWhereHas('departments', function ($q) use ($departmentId) {
                    $q->whereIn('department.id', $departmentId);
                });
            })
            ->with(['roles' => function ($q) {
                $q->where('name', 'Engineer');
            }])->get();
            $projects = Project::whereHas('enterprises', function ($query) use ($enterpriseId) {
                $query->whereIn('enterprise.id', $enterpriseId);
            })->orWhereHas('departments', function ($query) use ($departmentId) {
                $query->whereIn('department.id', $departmentId);
            })->with(['enterprises', 'departments'])
            ->orderBy('id', 'desc')
            ->get();
            $etalons = Etalon::where('id_user', Auth::id())->get();

        }
        elseif(Auth::user()->roles->first()->name ==="Master")
        {
            $enterpriseId = Auth::user()->enterprises->pluck('id');
            $departmentId = Auth::user()->departments->pluck('id');
            $engs = User::whereHas('roles', function ($query) {
                $query->where('name', 'Engineer');
            })
            ->where(function ($query) use ($enterpriseId, $departmentId) {
                $query->whereHas('enterprises', function ($q) use ($enterpriseId) {
                    $q->whereIn('enterprise.id', $enterpriseId);
                })->orWhereHas('departments', function ($q) use ($departmentId) {
                    $q->whereIn('department.id', $departmentId);
                });
            })
            ->with(['roles' => function ($q) {
                $q->where('name', 'Engineer');
            }])->get();
            $projects = Project::whereHas('enterprises', function ($query) use ($enterpriseId) {
                $query->whereIn('enterprise.id', $enterpriseId);
            })->orWhereHas('departments', function ($query) use ($departmentId) {
                $query->whereIn('department.id', $departmentId);
            })->with(['enterprises', 'departments'])
            ->orderBy('id', 'desc')
            ->get();
            $etalons = Etalon::where('id_user', Auth::id())->get();
        }
        else{
             $projects = Project::where(function ($query) {
                $query->whereHas('enterprises', function ($q) {
                    $q->where('enterprise.id', Auth::user()->enterprises->first()->id);
                })->orWhereHas('departments', function ($q) {
                    $q->where('department.id', Auth::user()->departments->first()->id);
                });
            })->whereHas('tasks.users', function ($q) {
                $q->where('users.id', Auth::id());
            })
            ->with(['enterprises', 'departments'])
            ->orderBy('id', 'desc')
            //->with(['tasks'])
            ->get();


            $engs = User::whereHas('roles', function ($query) {
                $query->where('name', 'Engineer');
            })->whereHas('enterprises', function ($query) {
                $query->where('enterprise.id', Auth::user()->enterprises->first()->id);
            })->orWhereHas('departments', function ($query) {   
                $query->where('department.id', Auth::user()->departments->first()->id);
            })->with(['roles' => function ($q) {
                $q->where('name', 'Engineer');
            }])->get();
            $etalons = Etalon::where('id_user', Auth::id())->get();
        }

        return view('projects.index' , compact('projects' , 'engs', 'etalons'));
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
    public function store(Request $request)
    {
        

        $project = new Project();  
        if( $request->input('projectName') === null || $request->input('projectName') === '')
        {
            return redirect()->back()->withErrors(['projectName' => 'Назва проєкту є обов’язковою!']);
        }
        // остолне перевіряем що їснує якщо ні то нулл
    
        $project->name = $request->input('projectName')? $request->input('projectName') : "Без назви"; // Default value if not provided
       $locationInput = $request->input('location');
        if ($locationInput && !preg_match('/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/', $locationInput)) {
            $project->location = $locationInput; // збережи як є
        } else {
            $project->location = $locationInput ?? null;
        }
        $project->manager = $request->input('manager') ? $request->input('manager') : NULL; // Default value if not provided
        $project->start_date = $request->input('startDate') ? $request->input('startDate') : date('Y-m-d'); // Default to today if not provided
        $project->end_date = $request->input('endDate')? $request->input('endDate') : date('Y-m-d', strtotime('+1 month')); // Default to one month later if not provided
        $project->budget = $request->input('budget')? $request->input('budget') : 0; // Default to 0 if not provided
        $project->description = $request->input('description')? $request->input('description') : NULL; // Default value if not provided
        $project->status = 'active'; // Default status, can be changed later
        $project->save();
        $enterpriseId = Auth::user()->enterprises->pluck('id');
        $departmentId = Auth::user()->departments->pluck('id');
        $project->enterprises()->sync($enterpriseId);
        $project->departments()->sync($departmentId);
        return redirect()->route('projects.index')->with('success', 'Проєкт успішно створено!');

    }

    public function show(string $id)
    {
        //
        $project = Project::with('tasks')
        // tasks orderBy('status', 'asc') and ID
        ->with(['tasks' => function ($query) {
            $query->orderBy('status', 'asc')->orderBy('id', 'desc');
        }])
        ->findOrFail($id);
        $etalons = Etalon::where('id_user', Auth::id())->get();
        return view('projects.show', compact('project', 'etalons'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $project = Project::findOrFail($id);
       $enterprise_location = Auth::user()->enterprises->first()->location;
        return view('projects.edit', compact('project', 'enterprise_location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'projectName' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'startDate' => 'nullable|date', 
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);
        $project = Project::findOrFail($id);
        $project->name = $request->input('projectName');
        $project->location = $request->input('location');
        $project->manager = $request->input('manager');
        $project->start_date = $request->input('startDate');
        $project->end_date = $request->input('endDate');
        $project->budget = $request->input('budget');
        $project->description = $request->input('description');
        $project->status = 'active'; // Default status, can be changed later
        $project->save();
        return redirect()->route('projects.index')->with('success', 'Проєкт успішно оновлено!');            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Проєкт успішно видалено!');
    }
}

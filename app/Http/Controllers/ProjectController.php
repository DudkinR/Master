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
        //
        $engs = User::whereHas('roles', function ($query) {
            $query->where('name', 'Engineer');
        })->with(['roles' => function ($q) {
            $q->where('name', 'Engineer');
        }])->get();
         if(Auth::user()->roles->first()->name ==="Boss")
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

        }
       $projects = Project::all();
        return view('projects.index' , compact('projects' , 'engs'));
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
    
        $project->name = $request->input('projectName');
        
        $project->location = $request->input('location')? $request->input('location') : NULL; // Default value if not provided
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
        $project = Project::with('tasks')->findOrFail($id);
        $etalons = Etalon::All();
        return view('projects.show', compact('project', 'etalons'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
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

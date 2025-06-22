<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Etalon;
use App\Models\User; // Assuming you have a User model for masters
use Illuminate\Support\Facades\Auth; // For authentication

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
      
        if(Auth::user()->roles->first()->name ==="Boss")
        {
            $enterpriseId = Auth::user()->enterprises->pluck('id');
            $departmentId = Auth::user()->departments->pluck('id');
            return $projects = Project::with(['tasks', 'tasks.equipments', 'tasks.materials', 'tasks.machines'])
            ->get();
             $tasks = Task::with('enterprises')->get();
            $masters = User::whereHas('roles', function ($query) {
                $query->where('name', 'Master');
            })
            ->where(function ($query) use ($enterpriseId, $departmentId) {
                $query->whereHas('enterprises', function ($q) use ($enterpriseId) {
                    $q->whereIn('enterprise.id', $enterpriseId);
                })->orWhereHas('departments', function ($q) use ($departmentId) {
                    $q->whereIn('department.id', $departmentId);
                });
            })
            ->with(['roles' => function ($q) {
                $q->where('name', 'Master');
            }])->get();

            return view('tasks.index', compact('tasks' , 'masters'));
        }
        
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
    {        $request->validate([
            'order' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'project_id' => 'required|exists:projects,id',
        ]);
        $task = new Task();
        $task->order = $request->input('order');
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->start_date = $request->input('start_date');
        $task->end_date = $request->input('end_date');
        $task->estimated_hours = $request->input('estimated_hours');
        $task->actual_hours = $request->input('actual_hours');
        $task->progress = $request->input('progress', 0); // Default to 0 if not provided
        $task->quantity = $request->input('quantity', 0); // Default to 0 if not provided
        $task->unit = $request->input('unit', 'шт'); // Default to 'шт' if not provided
        $task->project_id = $request->input('project_id');
        $task->save();

        if(isset($request->etalon_task)){
            if(!Etalon::where('name', $task->name)->where('description', $task->description)->exists()) {
                $etalon = new Etalon();
                $etalon->name = $task->name;
                $etalon->description = $task->description;
                $etalon->estimated_hours = $task->estimated_hours;
                $etalon->actual_hours = $task->actual_hours;
                $etalon->quantity = $task->quantity;
                $etalon->unit = $task->unit;
                $etalon->save();
            }
        }

        $etalons = Etalon::all();
        $project = Project::findOrFail($task->project_id);
        $project->tasks()->attach($task->id, ['status' => 'active']); // Attach task to project with default status
        $enterpriseId = Auth::user()->enterprises->pluck('id');
        $departmentId = Auth::user()->departments->pluck('id');
        $task->enterprises()->sync($enterpriseId);
        $task->departments()->sync($departmentId);

        return redirect()->route('projects.show', [$project->id]) 
                         ->with('success', 'Завдання успішно створено!')
                         ->with('etalons', $etalons);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $task = Task::findOrFail($id)
            ->load('users','materials', 'machines', 'equipments');
        return view('tasks.show', compact('task'));



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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // assignMaster
    public function assignMaster(Request $request)
    {
       // return $request->all(); // For debugging purposes, remove this line in production
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'master_id' => 'required|exists:users,id',
        ]);
         $task = Task::findOrFail($request->input('task_id'));
         $master = User::findOrFail($request->input('master_id'));
         // clear users where role is master
        $task->users()->wherePivot('role', 'master')->detach();
        // attach master to task with role master
        $task->users()->attach($master->id, ['role' => 'master']);
        return redirect()->route('tasks.index')
                         ->with('success', 'Майстер успішно призначений до завдання!');
       
    }
    //Masterindex
    public function Masterindex()
    {
        // вибрати всіх мастеров
        $masters = User::whereHas('roles', function ($query) {
            $query->where('name', 'master');
        })->with('roles')
        // та всі їх завдання
        ->with('tasks')
        ->get();
        // повернути view з мастерами та їх завданнями
        return view('tasks.master_index', compact('masters'));
    }
    //Brigadirshow 
    public function Brigadirshow($id)
    {
        // вибрати завдання по id
        $task = Task::findOrFail($id)
            ->load('users'); // Завантажити користувачів та проект
        // повернути view з завданням
        $brigadirs = User::whereHas('roles', function ($query) {
            $query->where('name', 'brigadir');
        })->with('roles')->get();
        $workers = User::whereHas('roles', function ($query) {
            $query->where('name', 'worker');
        })->with('roles')->get();

        return view('tasks.brigadir_show', compact('task', 'brigadirs', 'workers'));
    }
    public function assignBrigadir(Request $request, )
    {
       
       // return $request->all(); // For debugging purposes, remove this line in production
        // Очистити попередніх бригадирів і працівників
        $task = Task::findOrFail($request->input('task_id'));
        $task->users()->wherePivot('role', 'brigadir')->detach();
        $task->users()->wherePivot('role', 'worker')->detach();

        // Призначити нового бригадира
        $task->users()->attach($request->input('brigadir_id'), ['role' => 'brigadir']);

        // Призначити працівників
        foreach ($request->input('workers') as $workerId) {
            $task->users()->attach($workerId, ['role' => 'worker']);
        }

        return redirect()->route('tasks.index')
                        ->with('success', 'Бригада успішно призначена!');
    }
}


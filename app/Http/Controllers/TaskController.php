<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Etalon;
use App\Models\Users; // Assuming you have a User model for masters

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::all();
        $masters =  Users::with('role')->where('role.name', 'master')->get(); // Assuming you have a User model with a role field

        return view('tasks.index', compact('tasks' , 'masters'));
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
}

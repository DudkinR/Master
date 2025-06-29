<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:255',
        ]);
        $equipment = new \App\Models\Equipment();
        $equipment->name = $validated['name'];
        $equipment->description = $validated['description'];
        $equipment->save();
        $task = Task::findOrFail($request->input('task_id'));
        $equipment->tasks()->attach($task->id, [
            'quantity' => $request->input('quantity', 0), // Default to 0
            'unit' => $request->input('unit', 'шт'), // Default to 'шт'
        ]);
        $project = Project::findOrFail($request->input('project_id'));
          if ($request->input('views') && $request->input('views') === 'showtask') {
            
            return redirect()->route('tasks.show', $task->id)
                ->with('success', 'Machine created successfully!')
                ->withInput(['project_id' => $request->input('project_id')])
                ->withInput(['task_id' => $request->input('task_id')]);
        } else {
           return redirect()->route('tasks.edit', $task->id)
                    ->with('success', 'Material created successfully!')                         
                ->withInput(['project_id' => $request->input('project_id')])
                ->withInput(['task_id' => $request->input('task_id')]);
        }

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
        $equipment = Equipment::findOrFail($id);
        $task = $equipment->tasks()->first(); // Assuming each equipment is linked to one task
        $equipment->tasks()->detach($task->id); // Detach the equipment from the task
        $equipment->delete(); // Delete the equipment   
        return redirect()->route('tasks.edit', $task->id)
            ->with('success', 'Equipment deleted successfully!');

    }
}

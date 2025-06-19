<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;
use App\Models\Task;

class MaterialController extends Controller
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
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:255',
        ]);

        $Material = new \App\Models\Material();
        $Material->name = $validated['name'];
        $Material->description = $validated['description'];
        $Material->unit = $validated['unit'];
        $Material->save();
        $task= Task::findOrFail($request->input('task_id'));
        $Material->tasks()->attach($task->id, [ 
            'quantity' => $request->input('quantity', 0), // Default to 0
            'unit' => $request->input('unit', 'шт'), // Default to 'шт'
        ]);
        $project = Project::findOrFail($request->input('project_id'));
        return redirect()->route('projects.show',$project->id) 
                         ->with('success', 'Material created successfully!')                         
                        ->withInput(['project_id' => $request->input('project_id')])
                        ->withInput(['task_id' => $request->input('task_id')]);


        
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

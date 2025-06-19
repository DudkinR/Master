<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Etalon;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $projects = Project::all();
        return view('projects.index' , compact('projects'));
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
        $request->validate([
            'projectName' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'startDate' => 'nullable|date', 
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);
        $project = new Project();  
        $project->name = $request->input('projectName');
        $project->location = $request->input('location');
        $project->manager = $request->input('manager');
        $project->start_date = $request->input('startDate');
        $project->end_date = $request->input('endDate');
        $project->budget = $request->input('budget');
        $project->description = $request->input('description');
        $project->status = 'active'; // Default status, can be changed later
        $project->save();
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

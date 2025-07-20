<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; // Assuming you have a Project model
use App\Models\User; // Assuming you have a User model
use App\Filters\ProjectFilter; // Assuming you have a ProjectFilter for filtering projects

use Illuminate\Support\Facades\Auth; // For checking the authenticated user

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $projects = Project::with(['tasks', 'tasks.equipments', 'tasks.materials', 'tasks.machines'])
            ->get();
        $tasks = $projects->flatMap(function ($project) { return $project->tasks;});
        $masters = User::whereHas('roles', function ($query) {
            $query->where('name', 'Master');
        })->get();
        if(Auth::user()->roles->first()->name === "Boss") {
          return view('reports.index_boss', compact('projects', 'tasks', 'masters'));          
        } 
        elseif(Auth::user()->roles->first()->name === "Master") {
            return view('reports.index_master', compact('projects', 'tasks', 'masters'));
        }
        elseif(Auth::user()->roles->first()->name === "Brigadir") {
            return view('reports.index_brig', compact('projects', 'tasks', 'masters'));
        }
        elseif(Auth::user()->roles->first()->name === "Engineer") {
            return view('reports.index_eng', compact('projects', 'tasks', 'masters'));
        }
        else {
            return view('reports.index_guest', compact('projects', 'tasks', 'masters'));
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
    {
        //
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

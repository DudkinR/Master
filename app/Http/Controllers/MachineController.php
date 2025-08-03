<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Machine;
use App\Models\TaskActionLog;
use App\Models\TaskAction;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
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
        $find_old_copy = Machine::where('name', $validated['name'])->whereHas('tasks', function ($query) use ($request) {
            $query->where('tasks.id', $request->input('task_id'));
        })->first();
        if ($find_old_copy) {
            return redirect()->back()
                ->with('error', 'Machine with this name already exists!')
                ->withInput(['task_id' => $request->input('task_id')]);
        }
        $machine = new Machine();
        $machine->name = $validated['name'];
        $machine->description = $validated['description'];
         $machine->save();
        $task = Task::findOrFail($request->input('task_id'));
        $machine->tasks()->attach($task->id, [
            'quantity' => $request->input('quantity', 0), // Default to 0
            'unit' => $request->input('unit', 'шт'), // Default to 'шт'
        ]);
        //$project = Project::findOrFail($request->input('project_id'));
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
        $machine = Machine::findOrFail($id);
        $task= $machine->tasks->first(); // Get the first task associated with this machine
        $task ->machines()->detach($machine->id); // Detach the machine from the task
        $machine->delete(); // Delete the machine record
        return redirect()->route('tasks.edit', $task->id)
            ->with('success', 'Machine deleted successfully!');
    }

    public function start(Request $request)
    {
         $task = Task::findOrFail($request->input('task_id'));
        $machine = Machine::findOrFail($request->input('machine_id'));        
        TaskActionLog::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'machine_start',
            'comment' => "Машина з ID {$machine->name} запущена",
            'location' => null,
            'coordinates' => null, // можна передати координати
                ]);
        TaskAction::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'start',
            'comment' => 'Початок роботи машини ' . $machine->name,                                      
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Machine started successfully',
            'task_id' => $request->input('task_id'),
            'machine_id' => $machine->id,
            'action_type' => 'start',
            'location' => null,
             'coordinates' => null, // можна передати координати
        ]);
     
    }
    public function pause(Request $request)
    {
      //   return $request->all();
        $task = Task::findOrFail($request->input('task_id'));
        $machine = Machine::findOrFail($request->input('machine_id'));
        TaskActionLog::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'machine_pause',
            'comment' => "Машина з ID {$machine->name} призупинена",
           'location' => null,
                    'coordinates' => null, // можна передати координати
        ]);
        TaskAction::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'machine_pause',
            'comment' => 'Призупинення роботи машини ' . $machine->name,                                      
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Machine paused successfully',
            'task_id' => $request->input('task_id'),
            'machine_id' => $machine->id,
        ]);

    }
    public function stop(Request $request)
    {
       //  return $request->all();
        $task = Task::findOrFail($request->input('task_id'));
        $machine = Machine::findOrFail($request->input('machine_id'));
        TaskActionLog::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'machine_end',
            'comment' => "Машина з ID {$machine->name} зупинена",
            'location' => null,
                    'coordinates' => null, // можна передати координати
        ]);
        TaskAction::create([
            'task_id' => $request->input('task_id'),
            'user_id' => Auth::id(),
            'action_type' => 'machine_end',
            'comment' => 'Зупинка роботи машини ' . $machine->name,                                      
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Machine stopped successfully',
            'task_id' => $request->input('task_id'),
            'machine_id' => $machine->id,
        ]);
    }
}

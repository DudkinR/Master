<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\Problem;
use App\Models\Enterprise;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Material;
use App\Models\Machine;
use App\Models\Project;
use App\Models\Role;
use App\Models\TaskActionLog;
use Carbon\Carbon;

class TaskActionLogController extends Controller
{
    
    //
    public function magazine(Request $request)
    {      
     if(Auth::user()->roles->first()->name ==="Boss")
        {
            $enterpriseId = Auth::user()->enterprises->pluck('id');
            $departmentId = Auth::user()->departments->pluck('id');
            $tasks = Task::with('enterprises', 'departments', 'users', 'equipments', 'materials', 'machines', 'master', 'brigadir', 'workers')             
            ->where('status', '!=', 'completed')
             ->orderBy('created_at', 'desc')
             ->get();
            $personal = Auth::user()->allChildrenRecursive(3); // діти + їх діти + їх діти

        }
        elseif(Auth::user()->roles->first()->name ==="Master")
        {
            $tasks = Task::whereHas('users', function ($query) {
                $query->where('role', 'master')
                      ->where('user_id', Auth::id());
            })            
            ->where('status', '!=', 'completed')
            ->with(['users', 'equipments', 'materials', 'machines','master','brigadir','workers'])
            ->orderBy('created_at', 'desc')->get();   
             $personal = Auth::user()->allChildrenRecursive(2); 
     
        }
        elseif(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $active_tasks = Task::whereHas('users', function ($query) {
                $query->where('role', 'brigadir')
                      ->where('user_id', Auth::id());
            })->where('status', '=', 'in_progress')->get();
            if(!$active_tasks->isEmpty()) {
                $task = $active_tasks->first();                 
                $task = Task::with(['users', 'materials', 'machines', 'equipments'])->findOrFail($task->id);
                // Розрахунок витраченого часу
                $timeSpent = $task->actual_hours;
                $problems = Problem::where('task_id', $task->id)->get();
                return view('tasks.show', compact('task', 'timeSpent', 'problems'));
            }
           $personal= Auth::user()->children()->with('roles')->get();

        }
         $recentLogs = TaskActionLog::whereIn('user_id', $personal->pluck('id'))
        ->where('created_at', '>=', Carbon::now()->subDays(5))
        ->with('user', 'task')
        ->orderBy('created_at', 'desc')
        ->get();
       /// return compact( 'personal', 'recentLogs');
        return view('raports.magazine', compact( 'personal', 'recentLogs'));       
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
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

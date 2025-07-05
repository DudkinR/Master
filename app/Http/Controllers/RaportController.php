<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RaportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       if(Auth::user()->roles->first()->name ==="Admin")
        {
            $tasks = Task::with(['users', 'equipments', 'materials', 'machines'])->get();
          
        }
        if(Auth::user()->roles->first()->name ==="Boss")
        {
           $enterpriseIds = Auth::user()->enterprises->pluck('id');
           $departmentIds = Auth::user()->departments->pluck('id');
          // Витягуємо проєкти, які мають хоча б одне з підприємств або підрозділів
           $projects = Project::whereHas('enterprises', function ($q) use ($enterpriseIds) {
                    $q->whereIn('enterprise.id', $enterpriseIds);
                })
                ->orWhereHas('departments', function ($q) use ($departmentIds) {
                    $q->whereIn('department.id', $departmentIds);
                })
                ->get();

           // Витягуємо всі завдання, пов’язані з цими проєктами
           $tasks = Task::whereIn('project_id', $projects->pluck('id'))
    ->with([ 'actionLogs']) // без пробілу!
    ->get();  
        }
        if(Auth::user()->roles->first()->name ==="Master")
        {
            $enterpriseIds = Auth::user()->enterprises->pluck('id');
            $departmentIds = Auth::user()->departments->pluck('id');

            // Витягуємо проєкти, які мають хоча б одне з підприємств або підрозділів
            $projects = Project::whereHas('enterprises', function ($q) use ($enterpriseIds) {
                    $q->whereIn('enterprise.id', $enterpriseIds);
                })
                ->orWhereHas('departments', function ($q) use ($departmentIds) {
                    $q->whereIn('department.id', $departmentIds);
                })
                ->get();

            // Витягуємо всі завдання, пов’язані з цими проєктами
            $tasks = Task::whereIn('project_id', $projects->pluck('id'))
            // users == Auth::user()
                ->whereHas('users', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['users', 'equipments', 'materials', 'machines', 'actionLogs'])
                ->get();

        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $enterpriseIds = Auth::user()->enterprises->pluck('id');
            $departmentIds = Auth::user()->departments->pluck('id');

            // Витягуємо проєкти, які мають хоча б одне з підприємств або підрозділів
            $projects = Project::whereHas('enterprises', function ($q) use ($enterpriseIds) {
                    $q->whereIn('enterprise.id', $enterpriseIds);
                })
                ->orWhereHas('departments', function ($q) use ($departmentIds) {
                    $q->whereIn('department.id', $departmentIds);
                })
                ->get();

            // Витягуємо всі завдання, пов’язані з цими проєктами
            $tasks = Task::whereIn('project_id', $projects->pluck('id'))
            // users == Auth::user()
                ->whereHas('users', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['users', 'equipments', 'materials', 'machines'])
                ->get();
            
        }

        $raports = $this->reportForMaster($tasks);
        return view('raports.index', [
            'raports' => $raports,
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('raports.create');
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
        return view('raports.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('raports.edit', ['id' => $id]);
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
   public function reportForMaster($tasks)
{
    $today = now()->format('Y-m-d');

    $activeTasks = [];
    $inactiveTasks = [];
    $totalWorkers = collect();

    foreach ($tasks as $task) {
        // Перевірка, чи заплановане на сьогодні
        $isPlannedToday = $task['start_date'] <= $today && $task['end_date'] >= $today;

        // Фільтруємо логи лише за сьогодні
        $todayLogs = collect($task['action_logs'])->filter(function ($log) use ($today) {
            return \Illuminate\Support\Str::startsWith($log['created_at'], $today);
        });

        if ($todayLogs->isNotEmpty()) {
            $userIds = $todayLogs->pluck('user_id')->unique();
            $totalWorkers = $totalWorkers->merge($userIds);

            $activeTasks[] = [
                'task' => $task,
                'workers' => $userIds,
                'workers_count' => $userIds->count(),
            ];
        } elseif ($isPlannedToday) {
            $inactiveTasks[] = $task;
        }
    }

    return [
        'summary' => [
            'total_tasks' => count($tasks),
            'planned_today' => count($tasks), // можна уточнити окремо, якщо потрібно
            'active_tasks_count' => count($activeTasks),
            'inactive_tasks_count' => count($inactiveTasks),
            'total_workers' => $totalWorkers->unique()->count(),
        ],
        'active_tasks' => $activeTasks,
        'inactive_tasks' => $inactiveTasks,
        'planned_today' => $tasks, // або фільтрувати окремо
    ];
}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Problem;
use App\Exports\MagazineExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $projects = Project::with(['enterprises', 'departments'])->get();
        }
        elseif(Auth::user()->roles->first()->name ==="Boss")
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
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
        }
        elseif(Auth::user()->roles->first()->name ==="Engineer")
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
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
        }
        elseif(Auth::user()->roles->first()->name ==="Master")
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
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();

        }
        elseif(Auth::user()->roles->first()->name ==="Brigadir")
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
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
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
             
        }
        elseif(Auth::user()->roles->first()->name ==="Worker")
        {
            $tasks = Task::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['users', 'equipments', 'materials', 'machines'])
            ->get();
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
        }
        else
        {
            $tasks = Task::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['users', 'equipments', 'materials', 'machines'])
            ->get();
            $active_tasks = Task::where('status', 'in_progress')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $paused_tasks = Task::where('status', 'paused')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $completed_tasks = Task::where('status', 'completed')->with(['users', 'equipments', 'materials', 'machines'])->get();
            $end_day_tasks = Task::where('end_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();
            $start_day_tasks = Task::where('start_date', Carbon::today())->with(['users', 'equipments', 'materials', 'machines'])->get();    
        }

     $raports = $this->reportForMaster($tasks);
        return view('raports.index', [
            'raports' => $raports,
            'tasks' => $tasks,
            'projects' => $projects,
            'active_tasks' => $active_tasks,
            'paused_tasks' => $paused_tasks,
            'completed_tasks' => $completed_tasks,
            'end_day_tasks' => $end_day_tasks,
            'start_day_tasks' => $start_day_tasks,
        ]);
    }
    public function reportForMaster($tasks)
    {
        $today = now()->format('Y-m-d');
        $activeTasks = [];
        $inactiveTasks = [];
        $plannedTodayTasks = [];
        $totalWorkers = collect();
        $projectIds = collect();     
        $problemsIds = collect(); 
        $openProblems = collect();
        $personalIds = collect();
        foreach ($tasks as $task) {
            $isPlannedToday = $task['start_date'] <= $today && $task['end_date'] >= $today;

            if ($isPlannedToday) {
                $plannedTodayTasks[] = $task;
            }

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
           $projectIds = $projectIds->merge($task->projects->pluck('id'));
           $problemsIds = $problemsIds->merge($task->problems->pluck('id'));
           //openProblems
           $openProblems = $task->openProblems->pluck('id');
           $personalIds = $personalIds->merge($task->users->pluck('id'));
        }       
        $problemsIds = $problemsIds->merge($openProblems); // об'є
        $projectIds = $projectIds->unique(); // унікальні ID
        $projectStats = [
            'total' => $projectIds->count(),
            'active' => 0,
            'inactive' => 0,
            'completed' => 0,
        ];

        foreach ($projectIds as $project) {
            $pr = Project::find($project);  
            switch ($pr->status) {
                case 'active':
                    $projectStats['active']++;
                    break;
                case 'inactive':
                    $projectStats['inactive']++;
                    break;
                  case 'planned':
                    $projectStats['planned']++;
                    break;
                case 'completed':
                    $projectStats['completed']++;
                    break;
            }
        }


        return [
            'summary' => [
                // Проєкти
                
                'projects_total' => $projectStats['total'],
                'projects_active' => $projectStats['active'],
                'projects_inactive' => $projectStats['inactive'],
                'projects_completed' => $projectStats['completed'],
                // Завдання
                'total_tasks' => count($tasks),
                'planned_today_tasks' => count($plannedTodayTasks),
                'active_tasks_count' => count($activeTasks),
                'inactive_tasks_count' => count($inactiveTasks),

                // Працівники
                'total_workers_today' => $totalWorkers->unique()->count(),
                'total_workers'=> $totalWorkers->unique()->count(),
            ],
            'tasks' => $tasks,
            'active_tasks' => $activeTasks,
            'inactive_tasks' => $inactiveTasks,
            'projects' => $projectIds,
            'problems' => $problemsIds->unique(),
            'active_tasks' => $activeTasks,
            'inactive_tasks' => $inactiveTasks,
            'planned_today' => $plannedTodayTasks,
            '$openProblems' => $openProblems->unique(),
            'personals' => $personalIds->unique(),
        ];
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

    public function moment_raports()
    {
        // select 

        /* return json 
        return response()->json([
            ['code' => 'ALERT001', 'message' => 'Перевищення температури!'],
            ['code' => 'ALERT002', 'message' => 'Втрата зв\'язку з сервером!'],
            ['code' => 'ALERT003', 'message' => 'Несправність обладнання!'],
            ['code' => 'ALERT004', 'message' => 'Проблема з матеріалами!'],
        ]);*/
        return null;
    }
   
    public function exportExcel(Request $request)
    {
        // decode to massive
       $data = json_decode($request->input('data'), true);
        if (empty($data)) {
            return response()->json(['message' => 'No data provided for export'], 400);
        }
        return Excel::download(new MagazineExport(collect($data)), 'журнал.xlsx');
    }
    public function exportPDF(Request $request)
    {
         $data = json_decode($request->input('data'), true);
        if (empty($data)) {
            return response()->json(['message' => 'No data provided for export'], 400);
        }
        $pdf = Pdf::loadView('exports.magazine_pdf', ['data' => $data])
          ->setPaper('A4', 'portrait')
          ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('журнал.pdf');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Etalon;
use App\Models\User; // Assuming you have a User model for masters
use Illuminate\Support\Facades\Auth; // For authentication
use App\Models\TaskActionLog; // Assuming you have a TaskActionLog model for logging task actions
use App\Models\Problem;
use App\Models\Material;
use App\Models\Equipment;
use App\Models\Machine;
class TaskController extends Controller
{
    public function index()
    {
        //
        if(Auth::user()->roles->first()->name ==="Admin")
        {
            $tasks = Task::with(['users', 'equipments', 'materials', 'machines'])->get();
            $masters = User::whereHas('roles', function ($query) {
                $query->where('name', 'Master');
            })->with('roles')->get();
            return view('tasks.admin_index', compact('tasks', 'masters'));
        }
        if(Auth::user()->roles->first()->name ==="Boss")
        {
            $enterpriseId = Auth::user()->enterprises->pluck('id');
            $departmentId = Auth::user()->departments->pluck('id');
            $projects = Project::with(['tasks', 'tasks.equipments', 'tasks.materials', 'tasks.machines'])
            ->get();
             $tasks = Task::with('enterprises')->get();
            $masters = User::whereHas('roles', function ($query) {
                $query->where('name', 'Master');
            })
            ->where(function ($query) use ($enterpriseId, $departmentId) {
                $query->whereHas('enterprises', function ($q) use ($enterpriseId) {
                    $q->whereIn('enterprise.id', $enterpriseId);
                })->orWhereHas('departments', function ($q) use ($departmentId) {
                    $q->whereIn('department.id', $departmentId);
                });
            })
            ->with(['roles' => function ($q) {
                $q->where('name', 'Master');
            }])->get();
            return view('tasks.index', compact('tasks' , 'masters'));
        }
        if(Auth::user()->roles->first()->name ==="Master")
        {
            $tasks = Task::whereHas('users', function ($query) {
                $query->where('role', 'master')
                      ->where('user_id', Auth::id());
            })->with(['users', 'equipments', 'materials', 'machines'])->get();
            $brigadirs = Auth::user()->children()->whereHas('roles', function ($query) {
                $query->where('name', 'brigadir');
            })->with('roles')->get();
            return view('tasks.index', compact('tasks', 'brigadirs'));
            
        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $tasks = Task::whereHas('users', function ($query) {
                $query->where('role', 'brigadir')
                      ->where('user_id', Auth::id());
            })->with(['users', 'equipments', 'materials', 'machines'])->get();
            $workers = Auth::user()->children()->whereHas('roles', function ($query) {
                $query->where('name', 'worker');
            })->with('roles')->get();
             
            return view('tasks.index_brig', compact('tasks', 'workers'));
        }
        
    }
    public function create()
    {
        $enterprise_id = Auth::user()->enterprises->pluck('id');
        $department_id = Auth::user()->departments->pluck('id');
        $projects = Project::where(function ($query) use ($enterprise_id, $department_id) {
            $query->whereHas('enterprises', function ($q) use ($enterprise_id) {
                $q->whereIn('enterprise.id', $enterprise_id);
            })->orWhereHas('departments', function ($q) use ($department_id) {
                $q->whereIn('department.id', $department_id);
            });
        })->get();
        return view('tasks.create', compact('projects'));
    }
    public function store(Request $request)
    {       
      //  return $request->all(); // For debugging purposes, remove this line in production
        $log="";
        $task = new Task();
        $task->order = $request->input('order')? $request->input('order') : 0; // Default to 0 if not provided
        $task->name = $request->input('name')? $request->input('name') : 'Нове завдання'; // Default name if not provided
        $task->description = $request->input('description')? $request->input('description') : 'Опис не надано'; // Default description if not provided
        $task->start_date = $request->input('start_date')? $request->input('start_date') : now(); // Default to current date if not provided
        $task->end_date = $request->input('end_date')? $request->input('end_date') : now()->addDays(7); // Default to 7 days from now if not provided
        $task->estimated_hours = $request->input('estimated_hours')? $request->input('estimated_hours') : 0; // Default to 0 if not provided
        $task->actual_hours = $request->input('actual_hours')? $request->input('actual_hours') : 0; // Default to 0 if not provided

        $task->progress = $request->input('progress', 0)? $request->input('progress') : 0; // Default to 0 if not provided
        $task->quantity = $request->input('quantity', 0)? $request->input('quantity') : 0; // Default to 0 if not provided
        $task->unit = $request->input('unit')? $request->input('unit') : 'шт'; // Default to 'шт' if not provided
        $task->project_id = $request->input('project_id')? $request->input('project_id') : null; // Default to null if not provided
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
             $log.= "Завдання збережено як еталон: {$task->name} ({$task->description})";
        }

        $etalons = Etalon::all();
        $project = Project::findOrFail($task->project_id);
        $project->tasks()->attach($task->id, ['status' => 'active']); // Attach task to project with default status
        $enterpriseId = Auth::user()->enterprises->pluck('id');
        $departmentId = Auth::user()->departments->pluck('id');
        $task->enterprises()->sync($enterpriseId);
        $task->departments()->sync($departmentId);

        // Прив’язка завдання до проекту з додатковими даними
        $project = Project::findOrFail($task->project_id);
        $project->tasks()->attach($task->id, ['status' => 'active']);

        // Прив’язка підрозділів і підприємств (як у вашому коді)
        $enterpriseIds = Auth::user()->enterprises->pluck('id');
        $departmentIds = Auth::user()->departments->pluck('id');
        $task->enterprises()->sync($enterpriseIds);
        $task->departments()->sync($departmentIds);

        $materials = $request->input('materials', []);
        $equipments = $request->input('equipments', []);
        $machines = $request->input('machines', []);


        // Збереження матеріалів
        if (!empty($materials)) {
            foreach ($materials as $materialData) {
                $material = Material::firstOrCreate(
                    ['name' => $materialData['name'], 'description' => $materialData['description'] ?? ''],
                    ['unit' => $materialData['unit'] ?? 'шт']
                );
                $task->materials()->attach($material->id, [
                    'quantity' => $materialData['quantity'] ?? 0,
                    'unit' => $materialData['unit'] ?? 'шт',
                ]);
            }
             $log.= "Матеріали збережено: ";
        }

        // Збереження обладнання
        if (!empty($equipments)) {
            foreach ($equipments as $equipmentData) {
                $equipment = Equipment::firstOrCreate(
                    ['name' => $equipmentData['name'], 'description' => $equipmentData['description'] ?? ''],
                    ['unit' => $equipmentData['unit'] ?? 'шт']
                );
                $task->equipments()->attach($equipment->id, [
                    'quantity' => $equipmentData['quantity'] ?? 0,
                    'unit' => $equipmentData['unit'] ?? 'шт',
                ]);
            }
             $log.= "Обладнання збережено: " ;
        }

        // Збереження техніки
        if (!empty($machines)) {
            foreach ($machines as $machineData) {
                $machine = Machine::firstOrCreate(
                    ['name' => $machineData['name'], 'description' => $machineData['description'] ?? ''],
                    ['unit' => $machineData['unit'] ?? 'шт']
                );
                $task->machines()->attach($machine->id, [
                    'quantity' => $machineData['quantity'] ?? 0,
                    'unit' => $machineData['unit'] ?? 'шт',
                ]);
            }
             $log.= "Техніка збережена: ";
        }
        // if back_route in request, redirect to that route
        if ($request->has('back_route')) {
            return redirect($request->input('back_route'))
                            ->with('success', 'Завдання успішно створено!'. $log)
                            ->with('etalons', $etalons);
        }

        return redirect()->route('projects.show', [$project->id]) 
                        ->with('success', 'Завдання успішно створено!'. $log)
                        ->with('etalons', $etalons);


    }


    public function edit(string $id)
    {
        //
        $task = Task::findOrFail($id);
        
        $etalons = Etalon::all();
        $masters = User::whereHas('roles', function ($query) {
            $query->where('name', 'Master');
        })->with('roles')->get();
        return view('tasks.edit', compact('task', 'etalons', 'masters'));
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }
    public function assignMaster(Request $request)
    {//return
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'role' => 'required|in:master,brigadir,worker',
            'master_id' => $request->input('role') === 'worker'
                ? 'required|array'
                : 'required|exists:users,id',
            'master_id.*' => 'exists:users,id',
        ]);

        $task = Task::findOrFail($request->input('task_id'));
        $role = $request->input('role');

        if ($role === 'master') {
            // Вилучити попереднього майстра і додати нового
            $task->users()->wherePivot('role', 'master')->detach();
            $task->users()->attach($request->input('master_id'), ['role' => 'master']);


        } elseif ($role === 'brigadir') {
            $task->users()->wherePivot('role', 'brigadir')->detach();
            $task->users()->attach($request->input('master_id'), ['role' => 'brigadir']);
            $user = User::findOrFail($request->input('master_id'));
            // add children  to task
            $task->users()->attach($user->children->pluck('id'), ['role' => 'worker']);


        } elseif ($role === 'worker') {
            $task->users()->wherePivot('role', 'worker')->detach();
            foreach ($request->input('master_id') as $id) {
                $task->users()->attach($id, ['role' => 'worker']);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Успішно призначено!');
    }
    public function Masterindex()
    {
        // вибрати всіх мастеров
        $masters = User::whereHas('roles', function ($query) {
            $query->where('name', 'master');
        })->with('roles')
        // та всі їх завдання
        ->with('tasks')
        ->get();
        // повернути view з мастерами та їх завданнями
        return view('tasks.master_index', compact('masters'));
    }
    public function Brigadirshow($id)
    {
        // вибрати завдання по id
        $task = Task::findOrFail($id)
            ->load('users'); // Завантажити користувачів та проект
        // повернути view з завданням
        return $brigadir = $task->users()->wherePivot('role', 'brigadir')->first();
        $workers = User::whereHas('roles', function ($query) {
            $query->where('name', 'worker');
        })->with('roles')->get();

        return view('tasks.brigadir_show', compact('task', 'brigadirs', 'workers'));
    }
    public function assignBrigadir(Request $request, )
    {       
    }
    public function complete(Request $request, $id)
    {
        //return $request->all(); // For debugging purposes, remove this line in production
        $task = Task::findOrFail($id);
        // Перевірка, чи користувач є бригадиром цього завдання
        $brigadir = $task->users()->wherePivot('role', 'brigadir')->first();
        if ($brigadir && $brigadir->id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Тільки бригадир може завершити завдання'
            ], 403);    
        }
        // Перевірка, чи завдання вже завершено
        $lastAction = TaskActionLog::where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastAction && $lastAction->action_type === 'complete') {
            return response()->json([
                'success' => false,
                'message' => 'Завдання вже завершено нещодавно. Будь ласка, зачекайте перед повторним завершенням.'
            ], 429);
        }
        // Оновлення статусу завдання
        if ($request->progress == 100) {
            $task->status = 'completed';
        } else {
            $task->status = 'in_progress'; // Якщо прогрес не 100%, залишаємо статус "в процесі"
        }
        $task->save();
    }
    public function show(string $id)
    {
        // Завантажуємо завдання разом з усіма зв'язками
        $task = Task::with(['users', 'materials', 'machines', 'equipments'])->findOrFail($id);

        // Отримуємо всі дії поточного користувача по цьому завданню
        $allAction = TaskActionLog::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->get();

        // Розрахунок витраченого часу
        $timeSpent = 0;
        $startTime = null;

        foreach ($allAction as $action) {
            if ($action->action_type === 'start') {
                $startTime = $action->created_at;
            } elseif (in_array($action->action_type, ['pause', 'end_day'])) {
                if ($startTime && $action->created_at->gt($startTime)) {
                    $timeSpent += $action->created_at->diffInSeconds($startTime);
                    $startTime = null;
                }
            }
        }

        // Якщо останній start не завершений — додаємо час до теперішнього
        if ($startTime) {
            $timeSpent += now()->diffInSeconds($startTime);
        }

        // Остання дія по завданню (не лише поточного користувача)
        $lastAction = TaskActionLog::where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $problems = Problem::where('task_id', $task->id)->get();    

        return view('tasks.show', compact('task', 'timeSpent', 'lastAction', 'allAction', 'problems'));
    }
    public function start(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Оновлення статусу завдання
        $task->status = 'in_progress';
        $task->save();
        // перевірити чи є хтов ідсилає цій запит брігадиром цьго завдання
        $brigadir = $task->users()->wherePivot('role', 'brigadir')->first();
        if ($brigadir && $brigadir->id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Тільки бригадир може розпочати завдання'
            ], 403);
        }
        // перевірити що ця задача не вже розпочата отання  'action_type' !== 'start',
        $lastAction = TaskActionLog::where('task_id', $task->id)        
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastAction && $lastAction->action_type== 'start') {
            return response()->json([
                'success' => false,
                'message' => 'Завдання вже розпочато нещодавно. Будь ласка, зачекайте перед повторним запуском.'
            ], 429);
        }        
        // Фіксація початку роботи кожного працівника, хто позначений у запиті
        if ($request->has('worker_ids') && is_array($request->worker_ids)) {
            foreach ($request->worker_ids as $userId) {
                TaskActionLog::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'action_type' => 'start',
                    'comment' => 'Початок роботи',
                    'location' => $request->input('location') ?? null, // можна передати координати
                ]);
            }
        }
        $users = $task->users()->wherePivot('role', ['brigadir', 'worker'])->get();
        // якщо когось не має в запиті, то додати його $request->has('worker_ids') то TaskActionLog зафіксувати проблему 
        $missingUsers = $users->whereNotIn('id', $request->worker_ids ?? []);
        if ($missingUsers->isNotEmpty()) {
            foreach ($missingUsers as $user) {
                TaskActionLog::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                    'action_type' => 'прогул',
                    'comment' => 'Початок роботи, але користувач не вказаний у запиті',
                    'location' => $request->input('location') ?? null, // можна передати координати
                ]);
            }
        }
        $materials = $task->materials()->get();
        // якщо не має в запиті, то додати його $request->has('material_ids') то TaskActionLog зафіксувати проблему 
        if ($request->has('material_ids') && is_array($request->material_ids)) {
            foreach ($request->material_ids as $materialId) {
                if (!$task->materials()->where('id', $materialId)->exists()) {
                    TaskActionLog::create([
                        'task_id' => $task->id,
                        'user_id' => Auth::id(),
                        'action_type' => 'missing_material',
                        'comment' => "Матеріал з ID $materialId не знайдено у завданні",
                        'location' => $request->input('location') ?? null, // можна передати координати
                    ]);
                }
            }
        }
        $equipments = $task->equipments()->get();
        // equipment_ids
        if ($request->has('equipment_ids') && is_array($request->equipment_ids)) {
            foreach ($request->equipment_ids as $equipmentId) {
                if (!$task->equipments()->where('id', $equipmentId)->exists()) {
                    TaskActionLog::create([
                        'task_id' => $task->id,
                        'user_id' => Auth::id(),
                        'action_type' => 'missing_equipment',
                        'comment' => "Обладнання з ID $equipmentId не знайдено у завданні",
                        'location' => $request->input('location') ?? null, // можна передати координати
                    ]);
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Завдання успішно розпочато',
            'task' => $task
        ]);

    }
    public function pause(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $brigadir = $task->users()->wherePivot('role', 'brigadir')->first();
        if ($brigadir && $brigadir->id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Тільки бригадир може розпочати завдання'
            ], 403);
        }
        // перевірити що ця задача не вже розпочата отання  'action_type' !== 'start',
        $lastAction = TaskActionLog::where('task_id', $task->id)        
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastAction && $lastAction->action_type== 'pause') {
            return response()->json([
                'success' => false,
                'message' => 'Завдання вже розпочато нещодавно. Будь ласка, зачекайте перед повторним запуском.'
            ], 429);
        }        
        // Фіксація початку роботи кожного працівника, хто позначений у запиті
        if ($request->has('worker_ids') && is_array($request->worker_ids)) {
            foreach ($request->worker_ids as $userId) {
                TaskActionLog::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'action_type' => 'pause',
                    'comment' => 'Пауза роботи',
                    'location' => $request->input('location') ?? null, // можна передати координати
                ]);
            }
        }  
        return response()->json([
            'success' => true,
            'message' => 'Завдання успішно призупинено',
            'task' => $task
        ]);

    }
    public function end(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        // Перевірка, чи користувач є бригадиром цього завдання
        $brigadir = $task->users()->wherePivot('role', 'brigadir')->first();
        if ($brigadir && $brigadir->id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Тільки бригадир може завершити завдання'
            ], 403);        
        }
        // Перевірка, чи завдання вже завершено
        $lastAction = TaskActionLog::where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastAction && $lastAction->action_type === 'end') {
            return response()->json([
                'success' => false,
                'message' => 'Завдання вже завершено нещодавно. Будь ласка, зачекайте перед повторним завершенням.'
            ], 429);
        }
        // Оновлення статусу завдання
        $task->status = 'completed';
        $task->save();
        // Фіксація завершення роботи кожного працівника, хто позначений у запиті
        if ($request->has('worker_ids') && is_array($request->worker_ids))
        {
            foreach ($request->worker_ids as $userId) {
                TaskActionLog::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'action_type' => 'end',
                    'comment' => 'Завершення роботи',
                    'location' => $request->input('location') ?? null, // можна передати
                    'coordinates' => $request->input('coordinates') ?? null, // можна передати координати
                ]);

            }
            return response()->json([
                'success' => true,
                'message' => 'Завдання успішно завершено',
                'task' => $task
            ]);
        }
    }
    // reportIssue
    public function reportIssue(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $task = Task::findOrFail($id);
        $problem = new Problem();
        $problem->task_id = $task->id;
        $problem->title = $request->input('title');
        $problem->description = $request->input('description');
        $problem->status = 'open'; // Default status
        $problem->user_id = Auth::id(); // Assuming the user reporting the issue is the authenticated user
        $problem->save();

        return redirect()->route('tasks.show', [$task->id])
                         ->with('success', 'Проблема успішно зареєстрована!');
    }
}


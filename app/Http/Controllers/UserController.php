<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// model user
use App\Models\User;
// model role
use App\Models\Role;
// model department
use App\Models\Department;
// model enterprise
use App\Models\Enterprise;
use App\Filters\EnterpriseFilter;
use App\Filters\RoleFilter;
// DepartmentFilter
use App\Filters\DepartmentFilter;
use App\Filters\UserFilter;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
{
    $enterprises = Enterprise::all();
    $departments = Department::all();
    $roles = Role::all(); 
    

    // Отримати базових користувачів відповідно до ролі
    $role = Auth::user()->roles->first()->name;

    switch ($role) {
        case 'Boss':
        case 'Master':
        case 'Brigadir':
           $childIds = User::find( Auth::user()->id)->children()->pluck('users.id');

            $users = User::whereIn('id', $childIds)
                ->with(['roles', 'departments', 'enterprises', 'children']);
            break;

        case 'Engineer':
            $users = User::with(['roles', 'departments', 'enterprises']);
            $users = (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))->handle($users);
            $users = (new DepartmentFilter(Auth::user()->departments->pluck('id')))->handle($users);
            $users = $users->whereHas('roles', function ($query) {
                $query->whereIn('roles.name', ['Boss', 'Master', 'Brigadir', 'Worker']);
            });
            break;

        default:
            $users = User::with(['roles', 'departments', 'enterprises', 'children']);
            break;
    }

    // 🔍 Пошук
    if ($request->input('search') !== null) {
        $users = (new UserFilter($request))->apply($users);
    }

    // Додаткові фільтри
    if ($request->has('roles')) {
        $users = (new RoleFilter($request->roles))->handle($users);
    }

    if ($request->has('departments')) {
        $users = (new DepartmentFilter($request->departments))->handle($users);    
    }

    if ($request->has('enterprises')) {
        $users = (new EnterpriseFilter($request->enterprises))->handle($users);    
    }

    // Завантаження
    $users = $users->get(); 

    return view('users.index', compact('users', 'enterprises', 'departments', 'roles'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $enterprises = Enterprise::all();
        $departments = Department::all();
        $roles = Role::all();
        return view('users.create', compact('enterprises', 'departments', 'roles'));    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

    'enterprise.*' => ['exists:enterprise,id'],
    ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        // position
        $user->position = $request->input('position', ''); // Додаємо поле position, якщо воно не передано, то буде порожнім
        // status
        $user->status = $request->input('status', 'active'); // Додаємо поле status, якщо воно не передано, то буде 'active'
        // password
        $user->password = bcrypt($request->input('password'));
        // Save user
        $user->save();
        // Attach roles
        if($request->has('role')) {
            $user->roles()->attach($request->input('role'));
        }
        // Attach departments
        if($request->has('department')) {
            $user->departments()->attach($request->input('department'));
        }
        // Attach enterprises
        if($request->has('enterprise')) {
            $user->enterprises()->attach($request->input('enterprise'));
        }
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['roles', 'departments', 'enterprises', 'supervisors', 'children'])->findOrFail($id);
        $roles = Role::all();
        $enterprises = Enterprise::all();
        $departments = Department::all();
        $users = User::with(['roles', 'departments', 'enterprises', 'children'])->get(); // Отримуємо всіх користувачів, які не мають супервізорів
        $children= $user->children()->get();
        return view('users.show', compact('user', 'roles', 'enterprises', 'departments', 'users', 'children'));
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

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        // Update position
        $user->position = $request->input('position', ''); // Додаємо поле position, якщо воно не передано, то буде порожнім
        // Update status
        $user->status = $request->input('status', 'active'); // Додаємо поле status, якщо воно не передано, то буде 'active'
        // Update password
        if($request->input('password')!== null) {         
            $user->password = bcrypt($request->input('password'));
        }
        // Update roles
        if($request->has('role')) {
            $user->roles()->sync($request->input('role'));
        } else {
            $user->roles()->detach();
        }
        // Update departments
        if($request->has('department')) {
            $user->departments()->detach();
            $user->departments()->sync($request->input('department'));
        } 
        // Update enterprises
        if($request->has('enterprise')) {
            $user->enterprises()->detach();
            $user->enterprises()->sync($request->input('enterprise'));
        } 
        $user->save();
        // if $request->input('boss') !== null
        if($request->input('boss') !== null) {
            $boss = User::findOrFail($request->input('boss'));
            // Detach all current supervisors
            $user->supervisors()->detach();
            // Attach new boss
            $user->supervisors()->attach($boss);
        }
        // if $request->input('subordinates') subordinates[]
        if($request->input('subordinates')) {
            $subordinates = $request->input('subordinates');
            // Detach all current subordinates
            $user->children()->detach();
            // Attach new subordinates
            foreach ($subordinates as $subordinateId) {
                $subordinate = User::findOrFail($subordinateId);
                $user->children()->attach($subordinate);
            }
        } 
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);
        // Detach roles, departments, and enterprises
        $user->roles()->detach();
        $user->departments()->detach();
        $user->enterprises()->detach();
        // Delete user
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    // Form_teams 
    public function Form_teams()
    {
        // clear users
        $this->removeCycles(); // Видаляємо цикли в ієрархії користувачів
        
        if (Auth::user()->roles->first()->name === "Admin") {
            $healthUsers = User::where('status', 'sick')->get();
            $vacationUsers = User::where('status', 'vacation')->get();
            $users = User::select('users.*')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('roles.name', ['Boss', 'Master', 'Brigadir', 'Worker'])
            ->with(['roles', 'children'])
            ->whereDoesntHave('supervisors')
            ->orderBy('roles.id')
            ->get();
            // all users without supervisors
            $freeUsers = User::select('users.*')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('roles.name', ['Boss', 'Master', 'Brigadir', 'Worker'])
            ->whereDoesntHave('supervisors')
            ->with(['roles', 'children'])
            ->orderBy('roles.id')
            ->get();

    }

        if(Auth::user()->roles->first()->name ==="Boss" ||Auth::user()->roles->first()->name ==="Engineer") 
        {
            $users = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->get();
            // all users without supervisors
            $freeUsers = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->whereDoesntHave('supervisors')
            ->get();
            // Користувачі на лікарняному
            $healthUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
            ->handle(
                (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                    ->handle(User::where('status', 'sick'))
            )
            ->get();


            // Користувачі у відпустці
            $vacationUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
                ->handle(
                   ( new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                   ->handle(User::where('status', 'vacation'))
                )
                ->get();           
          
        }
    
         if(Auth::user()->roles->first()->name ==="Master")
        {           
            $users = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->get();
            // all users without supervisors
            $freeUsers = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->whereDoesntHave('supervisors')
            ->get();
            // Користувачі на лікарняному
            $healthUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
            ->handle(
                (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                    ->handle(User::where('status', 'sick'))
            )
            ->get();
            // Користувачі у відпустці
            $vacationUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
                ->handle(
                   ( new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                   ->handle(User::where('status', 'vacation'))
                )
                ->get();

            

        }
        if(Auth::user()->roles->first()->name ==="Brigadir")
        {
            $users = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->get();
            // all users without supervisors
            $freeUsers = User::find(Auth::user()->id)->children()
            ->with(['roles', 'departments', 'enterprises','children'])
            ->whereDoesntHave('supervisors')
            ->get();
            // Користувачі на лікарняному
            $healthUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
            ->handle(
                (new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                    ->handle(User::where('status', 'sick'))
            )
            ->get();
            // Користувачі у відпустці
            $vacationUsers = (new DepartmentFilter(Auth::user()->departments->pluck('id')))
                ->handle(
                   ( new EnterpriseFilter(Auth::user()->enterprises->pluck('id')))
                   ->handle(User::where('status', 'vacation'))
                )
                ->get();    
        }
        return view('users.teams', compact('users', 'freeUsers', 'healthUsers', 'vacationUsers'));

    }
        // connect.users
    public function connectUsers(Request $request)
    {
        
       
        foreach ($request->input('user_id') as $userId) {
            $child = User::findOrFail($userId); // Того кого перетягуємо
            if ($child) {
                $child->status= $request->input('status', 'active'); // статус по замовчуванню active
                $child->save(); // Зберігаємо статус     
                 $parentId = $request->input('parent_id');

                if ($parentId!=0) {
                    $parent = User::findOrFail($parentId);

                    // Знаходимо всіх користувачів, у яких цей child вже є як дитина, і видаляємо зв’язок
                    $previousParents = User::whereHas('children', function ($query) use ($child) {
                        $query->where('related_user_id', $child->id);
                    })->get();

                    foreach ($previousParents as $previousParent) {
                        $previousParent->children()->detach($child->id);
                    }

                    // Призначаємо нового батька
                    $parent->children()->attach($child->id);

                } else {
                    // Якщо parent_id немає — прибираємо батьків повністю (знімаємо з ієрархії)
                    $currentParents = User::whereHas('children', function ($query) use ($child) {
                        $query->where('related_user_id', $child->id);
                    })->get();

                    foreach ($currentParents as $p) {
                        $p->children()->detach($child->id);
                    }
                }           
            }
        }    
        return response()->json([
            'success' => true,
            'message' => 'Ієрархія оновлена успішно. status ' . $request->input('status', 'active'),
            'parent_id' => $request->input('parent_id'),
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status', 'active')
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    ///loadCsvForm resources\views\users\admin\loadcsv.blade.php
    public function loadCsvForm()
    {
        if (Auth::user()->roles->first()->name !== "Admin") {
            return redirect()->route('users.index')->with('error', 'You do not have permission to access this page.');
        }
        return view('users.admin.loadcsv');
    }
    // loadCsv
    public function loadCsv(Request $request)
    {
        if (Auth::user()->roles->first()->name !== "Admin") {
            return redirect()->route('users.index')->with('error', 'You do not have permission to access this page.');
        }
       if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
           
            $handle = fopen($fileTmpPath, 'r');

            if ($handle === false) {
                die('Не вдалося відкрити файл.');
            }

            $headerFound = false;
            $dataRows = [];
             $workerRole= Role::where('name', 'Worker')->first();
            while (($row = fgetcsv($handle, 0, ",")) !== false) {
                // structure of csv file 
                //tn name department2 department2 position
                // 12931,	Андрощук Андрій Васильович,	23 ЕРП,	РБУ	, водій навантажувача з потужністю двигуна до 73,5 кВт 4р.
                // email = tn .@khnpp.atom.gov.ua passw =tn role = Worker 
                $tn = trim($row[0]);
                $email = $tn . '@khnpp.atom.gov.ua';
                $name = trim($row[1]);
                $departmentId = 1;
                $enterprisesId = 1;
                $position = trim($row[4]);
                // Перевірка наявності користувача з таким же tn
                $existingUser = User::Where('email', $email)->first();
                if ($existingUser) {
                    // Якщо користувач вже існує, оновлюємо його дані
                    $existingUser->name = $name;
                    $existingUser->email = $email;
                    $existingUser->phone =  $tn; // Можливо, телефон це tn
                    $existingUser->password = bcrypt($tn); // Пароль за замовчування
                    $existingUser->position = $position; // Додаємо позицію
                    $existingUser->save();
                    // Додаємо роль, якщо її ще немає
                    $existingUser->roles()->detach(); // Видаляємо всі ролі, щоб уникнути дублювання
                    $existingUser->roles()->attach($workerRole);
                    // Додаємо департамент, якщо його ще немає
                    $existingUser->departments()->detach(); // Видаляємо всі департаменти, щоб уникнути дублювання
                    $existingUser->departments()->attach($departmentId);
                   
                    // Додаємо підприємство, якщо його ще немає
                    $existingUser->enterprises()->detach(); // Видаляємо всі підприємства, щоб уникнути дублювання
                    $existingUser->enterprises()->attach($enterprisesId);
                    
                } else {
                    // Якщо користувача немає, створюємо нового
                    $user = new User();
                    $user->name = $name;
                    $user->email = $tn . '@khnpp.atom.gov.ua';
                    $user->phone = $tn; // Можливо, телефон це tn
                    $user->password = bcrypt($tn); // Пароль за замовчування
                    $user->position = $position; // Додаємо позицію
                    $user->save();
                    // Додаємо роль
                    $workerRole = Role::where('name', 'Worker')->first();
                    if ($workerRole) {
                        $user->roles()->attach($workerRole);
                    }
                    // Додаємо департамент
                    $user->departments()->attach($departmentId);
                    // Додаємо підприємство
                    $user->enterprises()->attach($enterprisesId);
                }
                // Додаємо користувача до масиву для подальшої обробки
                $users[] = [
                    'tn' => $tn,
                    'name' => $name,
                    'email' => $tn . '@khnpp.atom.gov.ua',
                    'phone' => $tn, // Можливо, телефон це tn
                    'position' => $position,
                    'department_id' => $departmentId,
                    'enterprises_id' => $enterprisesId,
                ];
            }
        }
        
  

        return redirect()->route('users.index')->with('success', 'Users imported successfully from CSV.');
    }

    function removeCycles()
    {
        $users = User::with('children')->get();

        foreach ($users as $user) {
            foreach ($user->children as $child) {
                if ($child->hasInChildrenRecursive($user->id)) {
                    // 🔥 Видаляємо зв’язок, бо він створює цикл
                    $user->children()->detach($child->id);
                   // echo "Видалено цикл: {$user->id} → {$child->id}\n";
                }
            }
        }
    }
}

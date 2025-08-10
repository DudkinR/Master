<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnterpriseController;
// TaskActionLogController
use App\Http\Controllers\TaskActionLogController;
//RaportController
use App\Http\Controllers\RaportController;


Route::get('/',  [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/password/reset', function () {
    return view('auth.passwords.reset');
})->name('password.request');
Route::get('/help', function () {
    return view('help');
})->name('help');
// about
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();
//  якщо авторізований користувач 
Route::get('/test-error', function () {
    abort(400); // Або 500, 400, 403
});
Route::middleware(['auth'])->group(function () {
// Додайте тут маршрути, які потребують автентифікації
// resourse routes projects, equipment, tasks, roles , materials , machines, tasks
Route::resource('projects', ProjectController::class);
Route::resource('equipments', EquipmentController::class);
Route::resource('tasks', TaskController::class);
Route::resource('roles', RoleController::class);//->middleware('auth.role.redirect');
Route::resource('materials', MaterialController::class);
Route::resource('machines', MachineController::class);
Route::resource('raports', App\Http\Controllers\RaportController::class);
Route::post('assignMaster', [TaskController::class, 'assignMaster'])->name('tasks.assignMaster');
// master.index
Route::get('/master', [App\Http\Controllers\TaskController::class, 'Masterindex'])->name('tasks.Master');
// resources/views/tasks/brigadir_index.blade.php
Route::get('/brigadir', [App\Http\Controllers\TaskController::class, 'Brigadirindex'])->name('tasks.Brigadir');
// Brigadirshow
Route::get('/brigadir/{id}', [App\Http\Controllers\TaskController::class, 'Brigadirshow'])->name('tasks.Brigadirshow');
// tasks.assignBrigadir assignBrigadir
Route::post('assignBrigadir', [TaskController::class, 'assignBrigadir'])->name('tasks.assignBrigadir');
//profile.show
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
//settings.index
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
// boss.index
Route::get('/boss', [App\Http\Controllers\BossController::class, 'index'])->name('boss.index');
// ingeener.index
Route::get('/ingeener', [App\Http\Controllers\IngeenerController::class, 'index'])->name('ingeener.index');
// brigadir.index
Route::get('/brigadir', [App\Http\Controllers\BrigadirController::class, 'index'])->name('brigadir.index');
// master.index
Route::get('/master', [App\Http\Controllers\MasterController::class, 'index'])->name('master.index');
// worker.index
Route::get('/worker', [App\Http\Controllers\WorkerController::class, 'index'])->name('worker.index');
// users
Route::any('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
// user.show
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
// user.edit
Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
// user.update
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
// user.destroy
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
// user.create
Route::get('/usersnew', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
// user.store
Route::post('/usersnew', [App\Http\Controllers\UserController::class, 'store'])->name('usersstore');
// teams.index
Route::get('/Formteams', [App\Http\Controllers\UserController::class, 'Form_teams'])->name('teams.index');
// // connect.users post
Route::any('/connectusers', [App\Http\Controllers\UserController::class, 'connectUsers'])->name('connect.users');
// get loadcsv personals
Route::get('/loadcsv', [App\Http\Controllers\UserController::class, 'loadCsvForm'])->name('loadcsv.form');
// post loadcsv personals
Route::post('/loadcsv', [App\Http\Controllers\UserController::class, 'loadCsv'])->name('loadcsv');
Route::get('/reports', [App\Http\Controllers\RaportController::class, 'index'])->name('reports.index')->middleware('auth.role.redirect');
// machines.start
Route::post('/machines_start', [MachineController::class, 'start'])->name('machines.start');
// machines.stop
Route::post('/machines_stop', [MachineController::class, 'stop'])->name('machines.end');
// 'machines.pause
Route::post('/machines_pause', [MachineController::class, 'pause'])->name('machines.pause');
// tasks.report_issue
Route::post('/tasks/{id}/report_issue', [TaskController::class, 'reportIssue'])->name('tasks.report_issue');
// close_problem
Route::post('/close_issue', [TaskController::class, 'closeIssue'])->name('close_problem');
// пост tasks.complete
Route::post('/tasksComplete', [TaskController::class, 'complete'])->name('tasks.complete');
// tasks.startDay *********************************
Route::post('/tasks/{id}/startDay', [TaskController::class, 'startDay'])->name('tasks.startDay');
// tasks.endDay
Route::post('/tasks/{id}/endDay', [TaskController::class, 'endDay'])->name('tasks.endDay');
// tasks.issue
Route::post('/tasks/{id}/issue', [TaskController::class, 'issue'])->name('tasks.issue');
// tasks.missingResource
Route::post('/tasks/{id}/missingResource', [TaskController::class, 'missingResource'])->name('tasks.missingResource');
// tasks.machineStart
Route::post('/tasks/{id}/machineStart', [TaskController::class, 'machineStart'])->name('tasks.machineStart');
// tasks.machinePause
Route::post('/tasks/{id}/machinePause', [TaskController::class, 'machinePause'])->name('tasks.machinePause');
// tasks.machineEnd
Route::post('/tasks/{id}/machineEnd', [TaskController::class, 'machineEnd'])->name('tasks.machineEnd');
// tasks.pause
Route::post('/tasks/{id}/pause', [TaskController::class, 'pause'])->name('tasks.pause');
// tasks.end
Route::post('/tasks/{id}/end', [TaskController::class, 'end'])->name('tasks.end');
// пост tasks.start
Route::post('/tasksstart',  [TaskController::class, 'start'])->name('tasks.start');
Route::get('/task-action-logs', [TaskActionLogController::class,  'magazine'])->name('task-action-logs.store');
// task_repeat/7
Route::get('/task_repeat/{id}', [TaskController::class, 'repeat'])->name('task_repeat');
///export/excel
Route::post('/export/excel', [RaportController::class, 'exportExcel'])->name('export.excel');
//export/pdf
Route::post('/export/pdf', [RaportController::class, 'exportPdf'])->name('export.pdf');
//export/csv
Route::post('/export/csv', [RaportController::class, 'exportCsv'])->name('export.csv');
/// enterprises.create
Route::get('/enterprises/create', [EnterpriseController::class, 'create'])->name('enterprises.create');
Route::post('/enterprises', [EnterpriseController::class, 'store'])->name('enterprises.store');
Route::get('/enterprises', [EnterpriseController::class, 'index'])->name('enterprises.index');
Route::get('/enterprises/{id}', [EnterpriseController::class, 'show'])->name('enterprises.show');
Route::get('/enterprises/{id}/edit', [EnterpriseController::class, 'edit'])->name('enterprises.edit');
Route::put('/enterprises/{id}', [EnterpriseController::class, 'update'])->name('enterprises.update');
Route::delete('/enterprises/{id}', [EnterpriseController::class, 'destroy'])->name('enterprises.destroy');
// departments
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
// connect.departments
});
Route::any('/test', 
function () {
    $user = "DudkinRomanE";
    $chatId = "";
    $telegram ="8386059263:AAE205g6EdOfI7CvuGithXF9R1afmjJR8i0";
  $message = "Test message from Laravel";
    $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
        'chat_id' => $chatId,
        'text' => $message,
    ]);

    if ($response->successful()) {
        return "Повідомлення успішно відправлено!";
    } else {
        return "Помилка відправки: " . $response->body();
    } 
}
)->name('test');
/// moment_raports()
Route::any('/moment_raports', [RaportController::class, 'moment_raports'])->name('moment_raports');

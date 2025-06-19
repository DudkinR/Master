<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MachineController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/password/reset', function () {
    return view('auth.passwords.reset');
})->name('password.request');
// Auth routes
Auth::routes();
// resourse routes projects, equipment, tasks, roles , materials , machines, tasks
Route::resource('projects', ProjectController::class);
Route::resource('equipment', EquipmentController::class);
Route::resource('tasks', TaskController::class);
Route::resource('roles', RoleController::class);
Route::resource('materials', MaterialController::class);
Route::resource('machines', MachineController::class);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
// пост tasks.start
Route::post('/tasksstart/{id}', [TaskController::class, 'start'])->name('tasks.start');
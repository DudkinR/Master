<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// home page route
Route::get('/home', function () {
    return view('home');
})->name('home');
Route::get('/projects/create', function () {
    return view('projects.create');
})->name('projects.create');
Route::get('/masters/create', function () {
    return view('masters.create');
})->name('masters.create');
Route::get('/objects/create', function () {
    return view('objects.create');
})->name('objects.create');
Route::get('/tasks/create', function () {
    return view('tasks.create');
})->name('tasks.create');
Route::get('/roles/create', function () {
    return view('roles.create');
})->name('roles.create');
Route::get('/brigades/create', function () {
    return view('brigades.create');
})->name('brigades.create');
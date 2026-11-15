@extends('layouts.app')
@section('title', 'Завдання (Admin) ')
@section('content')
@php 
$companiesCount = App\Models\Enterprise::count();
$departmentsCount = App\Models\Department::count();
$usersCount = App\Models\User::count();
$adminsCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->count();
$managersCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Boss');
            })->count();
$engeniersCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Engineer');
            })->count();
$MastersCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Master');
            })->count();
$BrigadiersCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Brigadir');
            })->count(); 
$workersCount = App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Worker');
            })->count();
$projectsCount = App\Models\Project::count();
$tasksCount = App\Models\Task::count();
$masterTasksInProgress = App\Models\Task::where('progress', '>', 0)->count();
$activeWorks = 0;
@endphp            
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif
   <div class="container my-5">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row g-4">

        <!-- Секція 1: Загальна інформація -->
        <div class="col-md-6 col-xl-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"> {{ __('Registered') }}  </h5>
                    <p>Підприємства: {{ $companiesCount }}</p>
                    <p>Підрозділи: {{ $departmentsCount }}</p>
                    <p>Користувачі: {{ $usersCount }}</p>
                    <p>Адміни: {{ $adminsCount }},  
                        Менеджери: {{ $managersCount }},
                        Інженери: {{ $engeniersCount }},
                        Майстри: {{ $MastersCount }},
                        Бригадири: {{ $BrigadiersCount }},
                        Робітники: {{ $workersCount }}
                </div>
            </div>
        </div>

        <!-- Секція 2: Проекти і Завдання -->
        <div class="col-md-6 col-xl-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"> {{ __('Projects and Tasks') }}</h5>
                    <p>Проекти: {{ $projectsCount }}</p>
                    <p>Завдання: {{ $tasksCount }}</p>
                </div>
            </div>
        </div>

        <!-- Секція 3: Мастер-завдання в роботі -->
        <div class="col-md-6 col-xl-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"> {{ __('Master Tasks In Progress') }}</h5>
                    <p>В роботі: {{ $masterTasksInProgress }}</p>
                </div>
            </div>
        </div>

        <!-- Секція 4: Поточні роботи -->
        <div class="col-md-6 col-xl-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"> {{ __('Active Works') }}    </h5>
                    <p>{{ $activeWorks }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

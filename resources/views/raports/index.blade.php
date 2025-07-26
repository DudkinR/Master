@extends('layouts.app')
@section('title', '')
@section('content')
<div class="container my-5">
    {{-- Сповіщення --}}
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif
    {{-- with('success' --}}
    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    {{-- create  --}}  
    <div class="row mb-4">
<div class="col-md-12">
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Зведена інформація</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col">
                    <div class="text-muted">
                        Усього Проектів / З завданнями

                    </div>
                    <div class="fw-bold fs-5 text-dark">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        {{$projects->count()}} / 
                        {{ $raports['summary']['projects_total'] }}
                        </a>
                    </div>
                    <div class="text-muted">Активні Проекти</div>
                    <div class="fw-bold fs-5 text-success">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        {{ $raports['summary']['projects_active'] }}
                        </a>
                     </div>
                    <div class="text-muted">Неактивні Проекти</div>
                    <div class="fw-bold fs-5 text-danger">
                        {{ $raports['summary']['projects_inactive'] }}
                    </div>
                    <div class="text-muted">Завершені Проекти</div>
                    <div class="fw-bold fs-5 text-secondary">
                        {{ $raports['summary']['projects_completed'] }}
                    </div>
                </div>
                <div class="col bg-light">
                    <div class="text-muted">Усього завдань</div>
                    <div class="fw-bold fs-5 text-dark">
                    
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_tasks'] }}
                        </a>
                        
                    </div>
              
                    <div class="text-muted">На сьогодні</div>
                    <div class="fw-bold fs-5 text-info">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['planned_today_tasks'] }}
                        </a>
                    </div>
             
                    <div class="text-muted">Активні</div>
                    <div class="fw-bold fs-5 text-success">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['active_tasks_count'] }}
                        </a>
                    </div>
                
                    <div class="text-muted">Неактивні</div>
                    <div class="fw-bold fs-5 text-danger">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['inactive_tasks_count'] }}
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="text-muted">Всього працівників</div>
                    <div class="fw-bold fs-5 text-dark">
                        <a href="{{ route('users.index') }}" class="text-dark text-decoration-none">
                        {{ count($raports['personals']) }}
                        </a>
                    </div>
                    <div class="text-muted">Задіяно людей</div>
                    <div class="fw-bold fs-5 text-warning">
                        <a href="{{ route('users.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_workers'] }} (
                            {{ $raports['summary']['total_workers_today'] }} сьогодні
                        )
                        </a>
                    </div>
                    <div class="text-muted">На лікарняному</div>
                    <div class="fw-bold fs-5 text-secondary"></div>
                    <div class="text-muted">Відпустка</div>
                    <div class="fw-bold fs-5 text-secondary"></div>
                </div>
                <div class="col bg-warning text-dark">
                    <div class="text-muted">Усьго проблемних питань</div>
                    <div class="fw-bold fs-5 text-dark">
                        {{ count($raports['problems']) }}
                    </div>
                    <div class="text-muted">Проблемні завдання</div>
                    <div class="fw-bold fs-5 text-danger"></div>
                    <div class="text-muted">Проблеми з персоналом</div>
                    <div class="fw-bold fs-5 text-danger"></div>
                    <div class="text-muted">Проблеми з техникою / ТМЦ / обладненням</div>
                    <div class="fw-bold fs-5 text-danger"></div>
            </div>
        </div>
    </div>

    @if(count($active_tasks))
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Активні завдання</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($active_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted">працівників: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> Name
                            : {{ implode(', ', $item->users->pluck('name')->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(count($paused_tasks))
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="bi bi-pause-circle-fill me-2"></i>Призупинені завдання</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($paused_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted">працівників: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> Name
                            : {{ implode(', ', $item->users->pluck('name')->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        </div>
    @endif
    @if(count($completed_tasks))
    <div class="card mb-4 border-secondary">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Завершені завдання</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($completed_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted">працівників: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> Name
                            : {{ implode(', ', $item->users->pluck('name')->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(count($end_day_tasks))
    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-calendar-check-fill me-2"></i>Завдання на сьогодні</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($end_day_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted">працівників: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> Name
                            : {{ implode(', ', $item->users->pluck('name')->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(count($start_day_tasks))
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-calendar-plus-fill me-2"></i>Завдання на початок дня</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($start_day_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted">працівників: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> Name
                            : {{ implode(', ', $item->users->pluck('name')->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
</div>
@endsection

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
                    <div class="text-muted">Усього Проектів</div>
                    <div class="fw-bold fs-5 text-dark">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        16
                        </a>
                    </div>
                    <div class="text-muted">Активні Проекти</div>
                    <div class="fw-bold fs-5 text-success">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        16
                        </a>
                     </div>
                    <div class="text-muted">Неактивні Проекти</div>
                    <div class="fw-bold fs-5 text-danger">0</div>
                    <div class="text-muted">Завершені Проекти</div>
                    <div class="fw-bold fs-5 text-secondary">24</div>
                </div>
                <div class="col">
                    <div class="text-muted">Усього завдань</div>
                    <div class="fw-bold fs-5 text-dark">
                    
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_tasks'] }}
                        </a>
                        
                    </div>
              
                    <div class="text-muted">На сьогодні</div>
                    <div class="fw-bold fs-5 text-info">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['planned_today'] }}
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
                        
                        51
                        </a>
                    </div>
                    <div class="text-muted">Задіяно людей</div>
                    <div class="fw-bold fs-5 text-warning">
                        <a href="{{ route('users.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_workers'] }} (42)
                        </a>
                    </div>
                    <div class="text-muted">На лікарняному</div>
                    <div class="fw-bold fs-5 text-secondary">2</div>
                    <div class="text-muted">Відпустка</div>
                    <div class="fw-bold fs-5 text-secondary">6</div>
                </div>
                <div class="col">
                    <div class="text-muted">Усьго проблемних питань</div>
                    <div class="fw-bold fs-5 text-dark">9</div>
                    <div class="text-muted">Проблемні завдання</div>
                    <div class="fw-bold fs-5 text-danger">2 (4)</div>
                    <div class="text-muted">Проблеми з персоналом</div>
                    <div class="fw-bold fs-5 text-danger">3 (51)</div>
                    <div class="text-muted">Проблеми з техникою / ТМЦ / обладненням</div>
                    <div class="fw-bold fs-5 text-danger">0 / 2 / 1</div>
            </div>
        </div>
    </div>

    @if(count($raports['active_tasks']))
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Активні завдання</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($raports['active_tasks'] as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item['task']['id']) }}" class="text-decoration-none">
                        <strong>{{ $item['task']['name'] }}</strong> —
                        <span class="text-muted">працівників: {{ $item['workers_count'] }}</span><br>
                        <small class="text-secondary">ID: {{ implode(', ', $item['workers']->toArray()) }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if(count($raports['inactive_tasks']))
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Неактивні (але заплановані на сьогодні)</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($raports['inactive_tasks'] as $task)
                <a href="{{ route('tasks.show', $task['id']) }}" class="text-decoration-none">
                    <li class="list-group-item">
                        <strong>{{ $task['name'] }}</strong><br>
                        <small class="text-muted">з {{ $task['start_date'] }} до {{ $task['end_date'] }}</small>
                    </li>
                </a>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

    </div>
    
</div>
@endsection

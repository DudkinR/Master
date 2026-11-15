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
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>{{ __('Summary Information') }}</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col">
                    <div class="text-muted">
                        {{ __('Total Projects / With Tasks') }}

                    </div>
                    <div class="fw-bold fs-5 text-dark">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        {{isset($projects) ? $projects->count() : 0 }}
                         / 
                        {{ $raports['summary']['projects_total'] }}
                        </a>
                    </div>
                    <div class="text-muted">{{ __('Active Projects') }}</div>
                    <div class="fw-bold fs-5 text-success">
                        <a href="{{ route('projects.index') }}" class="text-dark text-decoration-none">                            
                        {{ $raports['summary']['projects_active'] }}
                        </a>
                     </div>
                    <div class="text-muted">{{ __('Inactive Projects') }}</div>
                    <div class="fw-bold fs-5 text-danger">
                        {{ $raports['summary']['projects_inactive'] }}
                    </div>
                    <div class="text-muted">{{ __('Completed Projects') }}</div>
                    <div class="fw-bold fs-5 text-secondary">
                        {{ $raports['summary']['projects_completed'] }}
                    </div>
                </div>
                <div class="col bg-light">
                    <div class="text-muted">{{ __('Total Tasks') }}</div>
                    <div class="fw-bold fs-5 text-dark">
                    
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_tasks'] }}
                        </a>
                        
                    </div>
              
                    <div class="text-muted">{{ __('Planned for Today') }}</div>
                    <div class="fw-bold fs-5 text-info">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['planned_today_tasks'] }}
                        </a>
                    </div>
             
                    <div class="text-muted">{{ __('Active Tasks') }}</div>
                    <div class="fw-bold fs-5 text-success">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['active_tasks_count'] }}
                        </a>
                    </div>
                
                    <div class="text-muted">{{ __('Inactive Tasks') }}</div>
                    <div class="fw-bold fs-5 text-danger">
                        <a href="{{ route('tasks.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['inactive_tasks_count'] }}
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="text-muted">{{ __('Total Workers') }}</div>
                    <div class="fw-bold fs-5 text-dark">
                        <a href="{{ route('users.index') }}" class="text-dark text-decoration-none">
                        {{ count($raports['personals']) }}
                        </a>
                    </div>
                    <div class="text-muted">{{ __('People Utilized') }}</div>
                    <div class="fw-bold fs-5 text-warning">
                        <a href="{{ route('users.index') }}" class="text-dark text-decoration-none">
                        {{ $raports['summary']['total_workers'] }} (
                            {{ $raports['summary']['total_workers_today'] }} {{ __('Today') }}  
                        )
                        </a>
                    </div>
                    <div class="text-muted">{{ __('On Sick Leave') }}</div>
                    <div class="fw-bold fs-5 text-secondary"></div>
                    <div class="text-muted">{{ __('Vacation') }}</div>
                    <div class="fw-bold fs-5 text-secondary"></div>
                </div>
                <div class="col bg-warning text-dark">
                    <div class="text-muted">{{ __('Total Problems') }}</div>
                    <div class="fw-bold fs-5 text-dark">
                        {{ count($raports['problems']) }}
                    </div>
                    <div class="text-muted">{{ __('Problematic Tasks') }}</div>
                    <div class="fw-bold fs-5 text-danger"></div>
                    <div class="text-muted">{{ __('Personnel Issues') }}</div>
                    <div class="fw-bold fs-5 text-danger"></div>
                    <div class="text-muted">{{ __('Equipment Issues') }}</div>
                    <div class="fw-bold fs-5 text-danger"></div>
            </div>
        </div>
    </div>

    @if(count($active_tasks))
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>{{ __('Active Tasks') }}</h6>
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
            <h6 class="mb-0"><i class="bi bi-pause-circle-fill me-2"></i>{{ __('Paused Tasks') }}</h6>
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
            <h6 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>{{ __('Completed Tasks') }}</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($completed_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted"> {{ __('People Utilized') }}: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> {{ __('Name') }}
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
            <h6 class="mb-0"><i class="bi bi-calendar-check-fill me-2"></i>{{ __('Tasks for Today') }}</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($end_day_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted"> {{ __('People Utilized') }}: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> {{ __('Name') }}
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
            <h6 class="mb-0"><i class="bi bi-calendar-plus-fill me-2"></i>{{ __('Tasks for Start of Day') }}</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($start_day_tasks as $item)
                    <li class="list-group-item">
                        <a href="{{ route('tasks.show', $item->id) }}" class="text-decoration-none">
                        <strong>{{ $item->name }}</strong> —
                        <span class="text-muted"> {{ __('People Utilized') }}: {{ $item->users->count() }}</span><br>
                        <small class="text-secondary"> {{ __('Name') }}
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

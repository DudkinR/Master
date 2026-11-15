@extends('layouts.app')
@section('title', 'Tasks - Master')
@section('content')
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif
    <!-- всі мастера та  кількість завданнь  карточки-->
    <div class="row">
        @foreach ($masters as $master)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $master->name }}</h5>
                        <p class="card-text">{{ $master->email }}</p>
                        <p class="card-text"><strong>Tasks:</strong> {{ $master->tasks_count }}</p>
                        <a href="{{ route('tasks.masterTasks', $master->id) }}" class="btn btn-primary">View Tasks</a>
                    </div>  
                    <div class="card-footer">
                        <p><strong>Status:</strong> 
                            @if ($master->tasks_count > 0)
                                <span class="badge bg-success">Has Tasks</span>
                            @else
                                <span class="badge bg-secondary">No Tasks</span>
                            @endif
                        </p>
                        <a href="{{ route('tasks.assignMaster', $master->id) }}" class="btn btn-secondary">Assign Task</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@extends('layouts.app')
@section('title', 'Task Card')
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">          
            <h3 class="mb-4">Task Card: <strong>{{ $task->name }}</strong></h3>
            {{-- Статус завдання --}}
            @include('tasks.forms.status', ['task' => $task])
            {{-- Кнопки дій --}}
            @include('tasks.forms.buttons', ['task' => $task])
            <!-- Персонал -->
            @include('tasks.forms.workers', ['task' => $task])
            <!-- ТМЦ -->
            @include('tasks.forms.tmc', ['task' => $task])
            <!-- Обладнання -->
            @include('tasks.forms.equip', ['task' => $task])
            <!-- техника -->
            @include('tasks.forms.machines', ['task' => $task])
            <!-- Модальне завершення роботи -->
            @include('tasks.forms.end_work', ['task' => $task])
            <!-- Проблема -->
            @include('tasks.forms.problem', ['task' => $task])
            <!-- Довідка -->
            @include('tasks.forms.help', ['task' => $task])
        </div>
        <div class="col-md-4">
            @foreach ($problems as $problem)
                @if($problem->status === 'open')
                <div class="alert alert-danger mb-2 w-100" id="problem_{{ $problem->id }}">
                    <h6 class="alert-heading">{{ $problem->title }}</h6>
                    <p>{{ $problem->description }}</p>
                 <button class="btn btn-primary"
                        onclick='senddata("{{ route('close_problem') }}", @json(["problemId" => $problem->id]));
                        document.getElementById("problem_{{ $problem->id }}").remove();'>
                        Закрити проблему
                    </button>
                    @if ($problem->photos->isNotEmpty())
                        <div class="mb-2">
                            @foreach ($problem->photos as $photo)
                                <img src="{{ $photo->url }}" alt="Problem Photo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            @endforeach

            @if ($problems->isEmpty())
                <div class="alert alert-info w-100">Проблем немає.</div>
            @endif
        </div>
    </div>
</div>    
@include('tasks.forms.script', ['task' => $task])
@endsection
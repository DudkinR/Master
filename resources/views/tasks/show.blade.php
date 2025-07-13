@extends('layouts.app')
@section('title', 'Картка завдання')
@section('content')
<div class="container my-5">
    @if (session('status'))
    <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif
    <h3 class="mb-4">Картка завдання: <strong>{{ $task->name }}</strong></h3>
    {{-- Статус завдання --}}
    @include('tasks.forms.status', ['task' => $task])
    {{-- Кнопки дій --}}
    @include('tasks.forms.buttons', ['task' => $task, 'lastAction' => $lastAction])
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
    <!-- Коментарі -->
</div>
@include('tasks.forms.script', ['task' => $task])
@endsection
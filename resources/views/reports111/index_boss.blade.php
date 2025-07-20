@extends('layouts.app')
@section('title', 'Звіти')
@section('content')
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif
    <!-- кнопки з короткою інформаціей (Проекти, завдання, Роботи, Проблеми) 4 кнопки на весь екран -->
    <div class="row row-cols-2 g-4 w-100 text-center" style="max-width: 800px;">
        <div class="col">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-primary w-100 py-5 shadow-sm">
                📁 <br> Проєкти
            </a>
        </div>
        <div class="col">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-success w-100 py-5 shadow-sm">
                ✅ <br> Завдання
            </a>
        </div>
        <div class="col">
            <a href="#" class="btn btn-outline-warning w-100 py-5 shadow-sm">
                🛠 <br> Роботи
            </a>
        </div>
        <div class="col">
            <a href="#" class="btn btn-outline-danger w-100 py-5 shadow-sm">
                ❗<br> Проблеми
            </a>
        </div>
    </div>
</div>
@endsection
    
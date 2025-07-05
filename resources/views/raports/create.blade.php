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
            back to tasks
            <a type="button" class="btn btn-secondary w-100" href="{{ route('tasks.index') }}" style="text-decoration: none;">
                Назад 
            </a>            
        </div>
    </div>
    {{-- Створення нового завдання --}}
</div>
@endsection

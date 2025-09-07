@extends('layouts.app')
@section('title', ' Create New Enterprise')
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
            back to enterprises
            <a type="button" class="btn btn-secondary w-100" href="{{ route('enterprises.index') }}" style="text-decoration: none;">
                Назад 
            </a>            
        </div>
    </div>
    {{-- Створення нового підприємства --}}
    <form action="{{ route('enterprises.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Назва</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Опис</label>
            <textarea class="form-control" id="description" name="description" rows="3" ></textarea>
        </div>
        <div class="form-group">
            <label for="location">Локація</label>
            <input type="text" class="form-control" id="location" name="location" >
        </div>
        <button type="submit" class="btn btn-primary">Створити</button>
    </form>
</div>
@endsection

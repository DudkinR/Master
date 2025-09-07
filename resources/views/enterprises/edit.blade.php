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
            <a type="button" class="btn btn-secondary w-100" href="{{ route('enterprises.index') }}" style="text-decoration: none;">
                Назад 
            </a>            
        </div>
    </div>
    {{-- edit підприємства --}}
    <form action="{{ route('enterprises.update', $enterprise->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Назва</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $enterprise->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Опис</label>
            <textarea class="form-control" id="description" name="description" rows="3" >{{ $enterprise->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="location">Локація</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $enterprise->location }}" >
        </div>
        <button type="submit" class="btn btn-primary">Зберегти</button>
    </form>
</div>
@endsection

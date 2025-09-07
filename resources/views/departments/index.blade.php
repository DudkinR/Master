@extends('layouts.app')
@section('title', 'departments List')
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
            <h2 class="mb-4">Список підрозділів</h2>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">Додати новий підрозділ</a>
        </div>
    </div>
    {{-- Список підрозділів --}}
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Назва</th>
                        <th>Опис</th>
                 
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $enterprise)
                        <tr>
                            <td>
                                <a href="{{ route('departments.show', $enterprise) }}">
                                    {{ $enterprise->name }}
                                </a>
                            </td>
                            <td>{{ $enterprise->description }}</td>
                     
                            <td>
                                <a href="{{ route('departments.show', $enterprise) }}" class="btn btn-info">Переглянути</a>
                                <a href="{{ route('departments.edit', $enterprise) }}" class="btn btn-warning">Редагувати</a>
                                <form action="{{ route('departments.destroy', $enterprise) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Видалити</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            <a type="button" class="btn btn-secondary w-100" href="{{ route('departments.index') }}" style="text-decoration: none;">
                Назад 
            </a>            
        </div>
    </div>
</div>
@endsection

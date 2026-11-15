@extends('layouts.app')
@section('title', 'Enterprise')
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

    {{-- Підприємство --}}
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">{{ $enterprise->name }}</h2>
            <p><strong>{{ __('Description') }}:</strong> {{ $enterprise->description }}</p>
            <p><strong>{{ __('Location') }}:</strong> {{ $enterprise->location }}</p>
        </div>
    </div>
    {{-- Дії --}}
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('enterprises.edit', $enterprise) }}" class="btn btn-warning">{{ __('Edit') }}</a>
            <form action="{{ route('enterprises.destroy', $enterprise) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
            </form>
        </div>
    </div>
    {{-- Повернутися до списку --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <a type="button" class="btn btn-secondary w-100" href="{{ route('enterprises.index') }}" style="text-decoration: none;">
                {{ __('Back') }}
            </a>            
        </div>
    </div>
</div>
@endsection

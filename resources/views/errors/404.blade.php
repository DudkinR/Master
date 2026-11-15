@extends('layouts.app')

@section('title', '404 - Сторінка не знайдена')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">404 - {{ __('Page Not Found') }}</div>

                <div class="card-body">
                    <p>
                        {{ __('The page you are looking for does not exist.') }}    
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
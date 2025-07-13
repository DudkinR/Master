@extends('layouts.app')

@section('title', '404 - Сторінка не знайдена')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">404 - Сторінка не знайдена</div>

                <div class="card-body">
                    <p>Вибачте, але сторінка, яку ви шукаєте, не існує.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Повернутися на головну</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
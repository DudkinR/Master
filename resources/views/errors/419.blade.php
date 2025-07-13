@extends('layouts.app')
@section('title',   '419 - Сесія закінчилася')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">419 - Сесія закінчилася</div>

                <div class="card-body">
                    <p>Ваша сесія закінчилася. Будь ласка, оновіть сторінку або увійдіть знову.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Повернутися на головну</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

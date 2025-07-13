@extends('layouts.app')

@section('title',  '403 - Доступ заборонено')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">403 - Доступ заборонено</div>

                <div class="card-body">
                    <p>Вибачте, але у вас немає доступу до цієї сторінки.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Повернутися на головну</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
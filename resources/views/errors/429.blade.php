@extends('layouts.app')
@section('title',  '429 - Занадто багато запитів') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">429 - Занадто багато запитів</div>

                <div class="card-body">
                    <p>Вибачте, але ви перевищили ліміт запитів. Будь ласка, спробуйте пізніше.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Повернутися на головну</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title',  ' any - Внутрішня помилка сервера')  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> any - Внутрішня помилка сервера</div>

                <div class="card-body">
                    <p>Вибачте, але сталася внутрішня помилка сервера. Будь ласка, спробуйте пізніше.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Повернутися на головну</a>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection
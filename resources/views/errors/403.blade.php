@extends('layouts.app')

@section('title',  '403 - Доступ заборонено')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    403 - {{ __('Forbidden') }}
                    
                </div>

                <div class="card-body">
                    <p>
                        {{ __('Sorry, you do not have access to this page.')    }}
                        </p>
                    <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
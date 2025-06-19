@extends('app.layout')

@section('title', 'Головна сторінка')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <h1>Головна сторінка</h1>
        <p>Вітаємо на головній сторінці вашого додатку!</p>
        <p>Використовуйте навігаційне меню для доступу до різних розділів.</p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <ul>
                <li>
                    <a href="{{route('projects.create')}}">
                        Projects
                    </a>
                </li>
                <li>
                    <a href="{{route('masters.create')}}">
                        Masters
                    </a>
                </li>
                <li>
                    <a href="{{route('objects.create')}}">
                        Objects
                    </a>
                </li>
                <li>
                    <a href="{{route('tasks.create')}}">
                        Tasks
                    </a>
                </li>
                <li>
                    <a href="{{route('roles.create')}}">
                        Roles
                    </a>

                </li>
                <li>
                    <a href="{{route('brigades.create')}}">
                        Brigades
                    </a>
                </li>
                <li>
                    <a href="{{route('brigades.index')}}">
                        Brigades Index 
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Мастер') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Мастер') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        @php 
                        /*
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                               
                                @if (Auth::user()->roles->first())
                                    {{ Auth::user()->roles->first()->name }}
                                @else
                                    {{ __('No Role Assigned') }}
                                @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Home') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        {{ __('Profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('settings.index') }}">
                                        {{ __('Settings') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('help') }}">
                                        {{ __('Help') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('about') }}">
                                        {{ __('About') }}
                                    </a>
                                </div>
                            </li>
                        */
                        @endphp
                              @if (!Auth::user()->roles->first())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ __('No Role Assigned') }}
                                    </a>

                                </li>                            
                            @elseif(Auth::user()->roles->first()->name == 'Admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Admin Panel') }}</a>
                                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('projects.index') }}">
                                            {{ __('Projects') }}
                                        </a>
                                     
                                        <a class="dropdown-item" href="">
                                            {{ __('Objects') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                            {{ __('Tasks') }}
                                        </a>
                                        <a class="dropdown-item" href="">
                                            {{ __('Reports') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                         <!-- Формируем команди -->
                                         <a class="dropdown-item" href="{{ route('teams.index') }}">
                                            {{ __('Teams') }}
                                        </a>                                      

                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Boss')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Boss Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('projects.index') }}">
                                            {{ __('Projects') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                            {{ __('Tasks') }}
                                        </a>
                                        <a class="dropdown-item" href="">
                                            {{ __('Reports') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                         <!-- Формируем команди -->
                                         <a class="dropdown-item" href="{{ route('teams.index') }}">
                                            {{ __('Teams') }}
                                        </a>
              
                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Engineer')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Engineer Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('projects.index') }}">
                                            {{ __('Projects') }}
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                            {{ __('Tasks') }}
                                        </a>
                                         <a class="dropdown-item" href="{{ route('users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                        <!-- Формируем команди -->
                                         <a class="dropdown-item" href="{{ route('teams.index') }}">
                                            {{ __('Teams') }}
                                        </a>
                                       
                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Master')
                                 <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Master Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('projects.index') }}">
                                            {{ __('Projects') }}
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                            {{ __('Tasks') }}
                                        </a>
                                       
                                         <a class="dropdown-item" href="{{ route('users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                         <!-- Формируем команди -->
                                         <a class="dropdown-item" href="{{ route('teams.index') }}">
                                            {{ __('Teams') }}
                                        </a>
                                      
                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Brigadir')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Brigadir Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('projects.index') }}">
                                            {{ __('Projects') }}
                                        </a>
                                       
                                        <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                            {{ __('Tasks') }}
                                        </a>
                                       
                                       
                                         <!-- Формируем команди -->
                                         <a class="dropdown-item" href="{{ route('teams.index') }}">
                                            {{ __('Teams') }}
                                        </a>
                                         <a class="dropdown-item" href="{{ route('users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                       
                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Worker')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Worker Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        
                                    </div>
                                </li>
                            @elseif(Auth::user()->roles->first()->name == 'Guest')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ __('Boss Panel') }}</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                      
                                            
                                    </div>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Home') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

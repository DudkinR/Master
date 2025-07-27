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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
 @php
    $icons = [
                'Admin' => '👑👑👑',
                'Boss' => '👑',
                'Engineer' => '👨‍🔧',
                'Master' => '👷',
                'Brigadir' => '🛠️',
                'Worker' => '🔧',
                'Unassigned' => '🔍',
                'Guest' => '👤',
            ];
    @endphp
    
</head>
<body>
    <div id="app">

    <div class="container py-3">
    <div class="d-flex flex-column flex-md-row flex-wrap align-items-center justify-content-between gap-2">
              {{-- Блок із прізвищем і кнопкою logout --}}
        <div class="d-flex align-items-center gap-2 ms-md-auto">
            <span class="text-muted">
            @auth
                @if (Auth::check())
                    {{ Auth::user()->name }}
                    @if (isset($icons[Auth::user()->roles->first()->name]))
                        {{ $icons[Auth::user()->roles->first()->name] }}
                    @endif
                @else
                    {{ __('Guest') }}
                    @if (isset($icons['Guest']))
                        {{ $icons['Guest'] }}
                    @endif
                @endif
               
            @endauth 
         </span>
        </div>
        {{-- Група кнопок навігації --}}
        <div class="d-flex flex-wrap gap-2">
            @guest
                @if (Route::has('login'))
                    <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                @endif

                @if (Route::has('register'))
                    <a class="btn btn-secondary" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            @else
                @php $role = Auth::user()->roles->first()->name; @endphp

                @if (in_array($role, ['Admin', 'Boss', 'Engineer', 'Master']))
                    <a class="btn btn-info" href="{{ route('raports.index') }}">{{ __('Reports') }}</a>                    
                    <a class="btn btn-danger" href="">{{ __('Problems') }}</a>                    
                    <a class="btn btn-warning" href="{{ route('tasks.index') }}">{{ __('Tasks') }}</a>
                    <a class="btn btn-secondary" href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                    <a class="btn btn-success" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                   
                    
                @elseif (in_array($role, ['Brigadir', 'Worker']))
                    <a class="btn btn-warning" href="{{ route('tasks.index') }}">{{ __('Tasks') }}</a>
                    <a class="btn btn-info" href="{{ route('task-action-logs.store') }}">{{ __('Task Action Logs') }}</a>
                @endif

                @if ($role === 'Admin')
                    <a class="btn btn-secondary" href="{{ route('loadcsv.form') }}">{{ __('Load CSV') }}</a>
                @endif
            @endguest
        </div>

  

    </div>
</div>


            

        <main class="py-4">
            @yield('content')
        </main>
        </div>
            <div class="modal-footer flex-column">
             @auth   
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> 
                          @if (Auth::check())
                                {{ Auth::user()->name }}
                                @if (isset($icons[Auth::user()->roles->first()->name]))
                                    {{ $icons[Auth::user()->roles->first()->name] }}
                                @endif
                            @else
                                {{ __('Guest') }}
                                @if (isset($icons['Guest']))
                                    {{ $icons['Guest'] }}
                                @endif
                            @endif
                    </button>
                </form>
            @endauth
        <audio id="alertSound" src="/sounds/alert.mp3" preload="auto"
            style="display: none;" loop="false" volume="0.5" autoplay="false" muted="false" controls="true" 
            onerror="console.error('Audio file not found or cannot be played.');" 
            oncanplaythrough="this.play();" 
            onended="this.currentTime = 0; this.pause();"
        ></audio>
            </div>
        <script>
       /*    const url = '{{ route('moment_raports') }}'; // замініть на свій URL
            const notifications = []; // масив для зберігання сповіщень

            function fetchNotifications() {                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ requestTime: new Date().toISOString() }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data !== null && Array.isArray(data)) {
                        data.forEach(item => {
                            const existing = notifications.find(n => n.code === item.code);
                            if (!existing) {
                                // нове сповіщення
                                notifications.push({ ...item, viewed: false });
                                showNotification(item);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Помилка при отриманні сповіщення:', error);
                });
            }

            function showNotification(item) {
                // Відтворити звук
                const sound = document.getElementById('alertSound');
                sound.play();

                // Показати повідомлення (можна налаштувати за потреби)
                alert(`Нове сповіщення: ${item.message}`);

                // Позначити як переглянуте
                const index = notifications.findIndex(n => n.code === item.code);
                if (index !== -1) {
                    notifications[index].viewed = true;
                }
            }

            // Запуск кожну хвилину
            setInterval(fetchNotifications, 60000); // 60000 мс = 1 хвилина

            // Також можна викликати відразу при завантаженні сторінки
            fetchNotifications();
 */
        </script>
    </div>
</body>
</html>

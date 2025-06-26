@extends('layouts.app')
@section('title', 'Ієрархія користувачів')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    .node {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 8px;
        background-color: #fff;
        cursor: grab;
    }
    .boss { background-color: #c8e6c9; }
    .master { background-color: #fff59d; }
    .brigadier { background-color: #81d4fa; }
    .worker { background-color: #e0e0e0; }

    .children {
        margin-left: 20px;
        margin-top: 10px;
    }
</style>

<div class="container my-4">
    <h2>Ієрархія персоналу</h2>
    <ul id="tree" class="list-unstyled">
        @if($bosses==null )
            <li class="node unassigned">🔍 Немає босів</li>
        @else
            @foreach ($boses as $boss)
            <li class="node boss">
            👑 {{ $boss->name}} 
                @php 
                $users = $users->reject(function ($user) use ($boss) {
                    return $user->id === $boss->id;
                }); 
                $boses = $boses->reject(function ($user) use ($boss) {
                    return $user->id === $boss->id;
                });
                @endphp
                @foreach($boss->children as $master)
               
                    <li class="node master">
                        🛠️ {{ $master->name }}
                        @php 
                        $users = $users->reject(function ($user) use ($master) {
                            return $user->id === $master->id;
                        }); 
                        $masters = $master->reject(function ($user) use ($master) {
                            return $user->id === $master->id;
                        });
                        @endphp
                        @foreach($master->children as $brigadier)
                            <ul class="children list-unstyled">
                                <li class="node brigadier">
                                    👷 {{ $brigadier->name }}
                                    @php 
                                    $users = $users->reject(function ($user) use ($brigadier) {
                                        return $user->id === $brigadier->id;
                                    }); 
                                    $brigadiers = $brigadier->reject(function ($user) use ($brigadier) {
                                        return $user->id === $brigadier->id;
                                    });
                                    @endphp
                                    @foreach($brigadier->children as $worker)
                                        <ul class="children list-unstyled">
                                            <li class="node worker">🔧 {{ $worker->name }}</li>
                                        </ul>
                                        @php 
                                        $users = $users->reject(function ($user) use ( $worker) {
                                            return $user->id === $worker->id;
                                        }); 
                                        $workers = $worker->reject(function ($user) use ($worker) {
                                            return $user->id === $worker->id;
                                        });
                                        @endphp
                                    @endforeach
                                </li>
                            </ul>
                        @endforeach
                    </li>
                </ul>
            @endforeach    
            </li>
            @endforeach
        @endif
       @if($masters==null)
            <li class="node unassigned">🔍 Немає майстрів</li>
        @endif
        @foreach($masters as $master)
        <li class="node master">
            🛠️ {{ $master->name }}
            @php 
            $users = $users->reject(function ($user) use ($master) {
                return $user->id === $master->id;
            }); 
            $masters = $masters->reject(function ($user) use ($master) {
                return $user->id === $master->id;
            });
            @endphp
            @foreach($master->children as $brigadier)
            <ul class="children list-unstyled">
                <li class="node brigadier">
                    👷 {{ $brigadier->name }}
                    @php 
                    $users = $users->reject(function ($user) use ($brigadier) {
                        return $user->id === $brigadier->id;
                    }); 
                    $brigadiers = $brigadier->reject(function ($user) use ($brigadier   ) {
                        return $user->id === $brigadier->id;
                    });
                    @endphp
                    @foreach($brigadier->children as $worker)
                    <ul class="children list-unstyled">
                        <li class="node worker">🔧 {{ $worker->name }}</li>
                        @php 
                        $users = $users->reject(function ($user) use ($worker) {
                            return $user->id === $worker->id;
                        }); 
                        $workers = $worker->reject(function ($user) use ($worker) {
                            return $user->id === $worker->id;
                        });
                        @endphp
                    @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
        @endforeach
        @if($brigadiers== null)
            <li class="node unassigned">🔍 Немає бригадирів</li>
        @endif
        @foreach($brigadiers as $brigadier)
        <li class="node brigadier">
            👷 {{ $brigadier->name }} 
            @php 
           /* $excludedIds = $brigadiers->pluck('id'); 
            $users = $users->reject(fn($user) => $excludedIds->contains($user->id));
            $brigadiers = $brigadier->reject(function ($user) use ($brigadier) {
                return $user->id === $brigadier->id;
            });*/
            @endphp
            @foreach($brigadier->children as $worker)
            <ul class="children list-unstyled">
                <li class="node worker">🔧 {{ $worker->name }}</li>
                @php 
                $users = $users->reject(function ($user) use ($worker) {
                    return $user->id === $worker->id;
                }); 
                $workers = $worker->reject(function ($user) use ($worker) {
                    return $user->id === $worker->id;
                });
                @endphp
            @endforeach
            </ul>
        </li>
        @endforeach
        @if($workers->isEmpty())
            <li class="node unassigned">🔍 Немає робітників</li>
        @endif
        @foreach($workers as $worker)
        <li class="node worker">
            🔧 {{ $worker->name }}       
            @php /*
            $users = $users->reject(function ($user) use ($worker) {
                return $user->id === $worker->id;
            }); 
            $workers = $worker->reject(function ($user) use ($worker) {
                return $user->id === $worker->id;
            }); */
            @endphp
        </li>
        @endforeach
    </ul>
   
    <h4>🗃️ Не прив’язані</h4>
    <ul id="unassigned" class="list-unstyled">
      
        {{$users->count() }}
         @foreach ($users as $user)
          
                @if ($user->roles ->isEmpty())
                    <li class="node unassigned">🔍 {{ $user->name }}</li>   
                @elseif ($user->roles->first()->name  === 'Boss')
                    <li class="node boss">👑 {{ $user->name }}</li>
                @elseif ($user->roles->first()->name   === 'Master')
                    <li class="node master">🛠️ {{ $user->name }}</li>
                @elseif ($user->roles->first()->name   === 'Brigadir')
                    <li class="node brigadier">👷 {{ $user->name }}</li>
                @elseif ($user->roles->first()->name   === 'Worker')
                    <li class="node worker">🔧 {{ $user->name }}</li>               
                @endif
            
        @endforeach
    </ul>
</div>

<script>
    function makeSortable(el) {
        new Sortable(el, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onAdd: function (evt) {
                // автоматичне створення вкладеного списку при переносі
                const parent = evt.item;
                if (!parent.querySelector('.children')) {
                    const ul = document.createElement('ul');
                    ul.className = 'children list-unstyled';
                    parent.appendChild(ul);
                    makeSortable(ul);
                }
            }
        });
    }

    // Ініціалізація всіх існуючих вкладених списків
    document.querySelectorAll('ul').forEach(makeSortable);
</script>

@endsection

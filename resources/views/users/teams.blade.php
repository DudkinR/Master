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
@php
 $icons = [
            'Admin' => '👑👑👑',
            'Boss' => '👑',
            'Engineer' => '👨‍🔧',
            'Master' => '👷',
            'Brigadir' => '🛠️',
            'Worker' => '🔧',
            'Unassigned' => '🔍',
        ];
@endphp
<div class="container my-4">
    <h2>Ієрархія персоналу</h2>
    <ul id="tree" class="list-unstyled">
        @if(Auth::user()->roles->first()->name ==="Boss" || Auth::user()->roles->first()->name ==="Admin")            
            @if(empty($bosses_id)    )
             <li class="node unassigned">🔍 Немає босів</li>
            @else
                @foreach ($bosses_id as $boss_id)
                    @php
                    // $boss_id as key and array of children as value
                    if(!is_numeric($boss_id)) { 
                        $boss = $users->find($boss_id[0]);              
                    } else {
                        $boss = $users->find($boss_id);
                    }
                    @endphp
                    @if(!$boss)
                        <li class="node unassigned">🔍 Немає даних про боса з ID {{ $boss_id }}</li>
                        @continue
                    @endif
                <li class="node boss" data-id="{{ $boss_id }}">
                    {{ $icons['Boss']}} {{ $boss->name}}
                    <ul class="children list-unstyled" data-id="{{ $boss_id }}">
                        @if($boss->children->isNotEmpty())
                            @foreach ($boss->children as $childId)  
                                @include('users.partials.user-tree', ['userId' => $childId, 'users' => $users])
                            @endforeach
                        @else
                            <li class="node unassigned">🔍 Немає підлеглих</li>
                        @endif

                    </ul>
                </li>
                @endforeach
            @endif
        @elseif(Auth::user()->roles->first()->name ==="Master")
        <h1>
            Майстрі
        </h1>
         @foreach($users as $user)
            @if($user->roles->first()->name === "Master")
                <li class="node master" data-id="{{ $user->id }}">
                    {{ $icons['Master']}} {{ $user->name }}
                    <ul class="children list-unstyled" data-id="{{ $user->id }}">
                        @if($user->children->isNotEmpty())
                            @foreach ($user->children as $child)  
                                @include('users.partials.user-tree', ['parentId'=> $user->id,'userId' => $child->id])
                            @endforeach
                        @else
                            <li class="node unassigned">🔍 Немає підлеглих</li>
                        @endif
                    </ul>
                </li>
            @endif
        @endforeach
        @elseif(Auth::user()->roles->first()->name ==="Brigadir")
        <h1>
            Бригадири
        </h1>
         @foreach($users as $user)
            @if($user->roles->first()->name === "Brigadir")
                <li class="node brigadier" data-id="{{ $user->id }}">
                    {{ $icons['Brigadir']}} {{ $user->name }}
                    <ul class="children list-unstyled" data-id="{{ $user->id }}">
                        @if($user->children->isNotEmpty())
                            @foreach ($user->children as $child)  
                                @include('users.partials.user-tree', ['parentId'=> $user->id,'userId' => $child->id])
                            @endforeach
                        @else
                            <li class="node unassigned">🔍 Немає підлеглих</li>
                        @endif
                    </ul>
                </li>
            @endif
        @endforeach    
        @endif

    </ul>                
    <h4>🗃️ Не прив’язані</h4>
    <ul id="unassigned" class="list-unstyled">
        @if(Auth::user()->roles->first()->name ==="Master")
        
         @foreach($freeUsers as $usfuserer)
            @if($fuser->roles->first()->name !== "Master")
                <li class="node master" data-id="{{ $fuser->id }}">
                    {{ $icons[$fuser->roles->first()->name]}} {{ $fuser->name }}
                    <ul class="children list-unstyled" data-id="{{ $fuser->id }}">                       
                    </ul>
                </li>
            @endif
        @endforeach
        @endif
        @if(Auth::user()->roles->first()->name ==="Brigadir")        
            @foreach($freeUsers as $fuser)
                <li class="node brigadier" data-id="{{ $fuser->id }}">
                 
                    {{ $icons[$fuser->roles->first()->name]}}
                    {{ $fuser->name }}
                    <ul class="children list-unstyled" data-id="{{ $fuser->id }}">                       
                    </ul>
                </li>
            
            @endforeach
        @endif
    </ul>
</div>



<script>
   

    function makeSortable(el) {

         new Sortable(el, {
                group: 'nested',
                animation: 150,
                onAdd: function (evt) {
                    const draggedEl = evt.item;
                    const draggedId = draggedEl.dataset.id;
                    const targetParent = evt.to.closest('.node');
                    let parentId = targetParent?.dataset?.id;
                    const url = @json(route('connect.users'));


                   
                    if(draggedId)
                    {
                        if(!parentId) parentId = 0; // Якщо немає батьківського елемента, встановлюємо 0
                        console.log(`Зв'язування користувача ${draggedId} з ${parentId}`);
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                parent_id: parentId,
                                user_id: draggedId
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Помилка: ${response.status} - ${text}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Успішно оновлено:', data);
                        })
                        .catch(error => {
                            console.error('Помилка:', error);
                        });

                                
                    }
                    
                }
            });
        }
    
    // Ініціалізація всіх існуючих вкладених списків
   document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('ul').forEach(makeSortable);
});
</script>

@endsection

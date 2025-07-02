@extends('layouts.app')
@section('title', 'Ієрархія користувачів')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
    .node {
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 8px;
        background-color: #fff;
        cursor: grab;
    }
    .boss { background-color:rgb(235, 138, 108); }
    .master { background-color: #fff59d; }
    .brigadier { background-color: #81d4fa; }
    .worker { background-color:rgb(98, 233, 166); }
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
        @foreach($users as $user)
            @php
                if($user->roles->first()->name === "Master") {
                    $class_name ='master';
                    $icon= $icons['Master'];
                }
                elseif($user->roles->first()->name === "Boss") {
                    $class_name ='boss';
                    $icon= $icons['Boss'];
                }
                elseif($user->roles->first()->name === "Brigadir"){
                    $icon= $icons['Brigadir'];
                 $class_name ='brigadier';
                }
                elseif($user->roles->first()->name === "Worker") {
                    $class_name ='worker';
                    $icon= $icons['Worker'];
                }
                else {
                    $class_name = 'unassigned';
                    $icon= $icons['Unassigned'];
                }
            @endphp
                <li class="node {{$class_name}}" data-id="{{ $user->id }}">
            
                
                <ul class="children list-unstyled" data-id="{{ $user->id }}">
                    {{ $icon}} {{ $user->name }}
                <br> 
                <b>{{ $user->position}}</b>
                    @if($user->children->isNotEmpty())
                        @foreach ($user->children as $child)  
                            @include('users.partials.user-tree', ['parentId'=> $user->id,'userId' => $child->id])
                        @endforeach
                    @else
                        <li class="unassigned"></li>
                    @endif
                    </ul>
                </li>
            
        @endforeach
    </ul>    

   @if(Auth::user()->hasRole(['Admin', 'Boss', 'Master']))
    <div class="row">
        <div class="col-md-6 bg-warning">
            <h4>Лікарнянний</h4>
    <ul class="list-health">
        @if($healthUsers->isEmpty())
            <li class="unassigned">Немає користувачів на лікарняному</li>
        @else
            @foreach($healthUsers as $user)
                @php
                if($user->roles->first()->name === "Master") {
                    $class_name ='master';
                    $icon= $icons['Master'];
                }
                elseif($user->roles->first()->name === "Boss") {
                    $class_name ='boss';
                    $icon= $icons['Boss'];
                }
                elseif($user->roles->first()->name === "Brigadir"){
                    $class_name ='brigadier';
                    $icon= $icons['Brigadir'];
                }
                elseif($user->roles->first()->name === "Worker") {
                    $class_name ='worker';
                    $icon= $icons['Worker'];
                }
                else {
                    $class_name = 'unassigned';
                    $icon= $icons['Unassigned'];    
                }
                @endphp
                <li class="node {{$class_name}}" data-id="{{ $user->id }}">
                    {{ $icon }} {{ $user->name }}
                    <br>
                    <b>{{ $user->position }}</b>
                </li>
            @endforeach
        @endif
    </ul>
        </div>
        <div class="col-md-6 bg-success">
            <h4>Відпустка</h4>
    <ul class="list-vacation">
        @if($vacationUsers->isEmpty())
            <li class="unassigned">Немає користувачів у відпустці</li>
        @else
            @foreach($vacationUsers as $user)
                @php
                if($user->roles->first()->name === "Master") {
                    $class_name ='master';
                    $icon= $icons['Master'];
                }
                elseif($user->roles->first()->name === "Boss") {
                    $class_name ='boss';
                    $icon= $icons['Boss'];
                }
                elseif($user->roles->first()->name === "Brigadir"){
                    $class_name ='brigadier';
                    $icon= $icons['Brigadir'];
                }
                elseif($user->roles->first()->name === "Worker") {
                    $class_name ='worker';
                    $icon= $icons['Worker'];
                }
                else {
                    $class_name = 'unassigned';
                    $icon= $icons['Unassigned'];
                }
                @endphp
                <li class="node {{$class_name}}" data-id="{{ $user->id }}">
                    {{ $icon }} {{ $user->name }}
                    <b>{{ $user->position }}</b>
                </li>
            @endforeach
        @endif
    </ul>           
    </div>   
</div>
    @endif
</div>
<script>
    function makeSortable(el) {
         new Sortable(el, {
                group: 'nested',
                animation: 150,
                onAdd: function (evt) {
                    const draggedEl = evt.item;
                    const draggedId = draggedEl.dataset.id;
                    const targetList = evt.to;
                    const isVacation = targetList.classList.contains('list-vacation');
                    if (isVacation) {
                        console.log(`Користувача ${draggedId} кинули в список ВІДПУСТКИ`);
                    } else {
                        console.log(`Користувача ${draggedId} кинули в інший список`);
                    }
                    const isHealth = targetList.classList.contains('list-health');
                    if (isHealth) {
                        console.log(`Користувача ${draggedId} кинули в список ЛІКАРНЯННОГО`);
                    } else {
                        console.log(`Користувача ${draggedId} кинули в інший список`);
                    }

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
                                user_id: draggedId,
                                status: isVacation ? 'vacation' : (isHealth ? 'sick' : 'active')
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

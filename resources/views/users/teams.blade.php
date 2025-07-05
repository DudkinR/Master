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
    .hide {
        display: none !important;
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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#findChildrenModal" onclick="setUserId({{ $user->id }});">
                <b>{{ $user->position}}</b>
                </button>
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#findChildrenModal" onclick="setUserId({{ $user->id }});">
                    <b>{{ $user->position }}</b>
                    </button>
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
                    <br>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#findChildrenModal" onclick="setUserId({{ $user->id }})">
                    <b>{{ $user->position }}</b>
                    </button>
                </li>
            @endforeach
        @endif
    </ul>           
    </div>   
</div>
    @endif
</div>
{{-- modal window find children and send --}}
<div class="modal fade" id="findChildrenModal" tabindex="-1" aria-labelledby="findChildrenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="findChildrenModalLabel">Знайти підлеглих</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="findChildrenForm">
                    <div class="mb-3">                        
                        <label for="searchName"  id="LsearchName" class="form-label">Ім'я користувача</label>
                        <input type="text" class="form-control" id="searchName" name="searchName" placeholder="Введіть ім'я користувача">
                        <input type="hidden" class="form-control" id="userId" name="userId">
                    </div>
                    <div class="mb-3">
                        <label for="searchChildren" class="form-label">Вибрати</label>
                        <select class="form-select" id="searchChildren" name="searchChildren[]" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->position }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="isVacation" class="form-label">Відпустка</label>
                        <input type="checkbox" id="isVacation" name="isVacation">
                    </div>
                    <div class="mb-3">
                        <label for="isHealth" class="form-label">Лікарняний</label>
                        <input type="checkbox" id="isHealth" name="isHealth">
                    </div>
                    <button type="submit" class="btn btn-primary">Знайти</button>
                </form>
            </div>
        </div>
    </div>
<script>
     const users = @json($users);
    const healthUsers = @json($healthUsers);
    const vacationUsers = @json($vacationUsers);

    document.addEventListener('DOMContentLoaded', function () {
        const findChildrenForm = document.getElementById('findChildrenForm');

        // Уникаємо багаторазового додавання обробника
        if (findChildrenForm.dataset.listenerAdded !== "true") {
            findChildrenForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const searchName = document.getElementById('searchName');
                const searchChildren = document.getElementById('searchChildren');
                const userId = document.getElementById('userId').value;

                const user = users.find(user => user.id == userId);
              
                const childrenIds = Array.from(searchChildren.selectedOptions).map(option => option.value);

                if(user){
                    searchName.value = user.name;
                }
               
                const isVacation = document.getElementById('isVacation').checked;
                const isHealth = document.getElementById('isHealth').checked;

                sendPostRequest(userId, childrenIds, isVacation, isHealth);

                // Закриваємо модальне вікно після відправки
                const modal = document.getElementById('findChildrenModal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    bootstrap.Modal.getOrCreateInstance(modal).hide();
                }
            });

            // Маркер, що слухач додано
            findChildrenForm.dataset.listenerAdded = "true";
        }
    });

    function setUserId(userId) {       
        document.getElementById('userId').value = userId;
        const user = users.find(user => user.id == userId);
        if( !user) {
            console.error(`Користувача з ID ${userId} не знайдено`);            
            document.getElementById('LsearchName').classList.add('hide');
            document.getElementById('searchName').classList.add('hide');         
        }
        else{
        document.getElementById('LsearchName').classList.remove('hide');
        document.getElementById('searchName').classList.remove('hide');
        document.getElementById('searchName').value = user ? user.name : '';
        }
    }
    
    function makeSortable(el) {
         new Sortable(el, {
                group: 'nested',
                animation: 150,
                onAdd: function (evt) {
                    const draggedEl = evt.item;
                    const draggedIds = [draggedEl.dataset.id];
                   
                    const targetList = evt.to;
                    const isVacation = targetList.classList.contains('list-vacation');
                    if (isVacation) {
                        console.log(`Користувача ${draggedIds} кинули в список ВІДПУСТКИ`);
                    } else {
                        console.log(`Користувача ${draggedIds} кинули в інший список`);
                    }
                    const isHealth = targetList.classList.contains('list-health');
                    if (isHealth) {
                        console.log(`Користувача ${draggedIds} кинули в список ЛІКАРНЯННОГО`);
                    } else {
                        console.log(`Користувача ${draggedIds} кинули в інший список`);
                    }

                    const targetParent = evt.to.closest('.node');
                    let parentId = targetParent?.dataset?.id;
                    const url = @json(route('connect.users'));                   
                    if(draggedIds.length === 0) {
                        alert('Будь ласка, виберіть хоча б одного підлеглого');
                        return;
                    }
                    else
                    {
                        sendPostRequest(parentId, draggedIds, isVacation, isHealth);                              
                    }                    
                }
            });
        }    
    // Ініціалізація всіх існуючих вкладених списків
   document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('ul').forEach(makeSortable);
});
function sendPostRequest( parentId, droppedIds, isVacation, isHealth) {
    const url = @json(route('connect.users'));     
   
    if(!parentId) parentId = 0; // Якщо немає батьківського елемента, встановлюємо 0
        console.log(`Зв'язування користувача ${droppedIds}  ааа з ${parentId}`);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            parent_id: parentId,
            user_id: droppedIds,
            status: isVacation ? 'vacation' : (isHealth ? 'sick' : 'active')
        })
    })    
    .catch(error => {
        console.error('Помилка при відправці запиту:', error);
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
</script>

@endsection

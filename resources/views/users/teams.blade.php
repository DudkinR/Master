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

        @if(empty($bosses_id)    )
            <li class="node unassigned">🔍 Немає босів</li>
        @else
            @foreach ($bosses_id as $boss)
               <li class="node boss" data-id="{{ $users->find($boss)->id }}">
                   👑 {{ $users->find($boss)->name}}
                   <ul class="children list-unstyled" data-id="{{ $users->find($boss)->id }}">
                       @if (!$users->find($boss)->children->isEmpty())
                           @foreach ($users->find($boss)->children as $child)
                               @include('users.partials.user-tree', ['userId' => $child->id, 'users' => $users])
                           @endforeach
                       @endif
                   </ul>
               </li>

            @endforeach
        @endif
    </ul>
                
    <h4>🗃️ Не прив’язані</h4>
    <ul id="unassigned" class="list-unstyled">
         @if($users_id->isEmpty())
            <li class="node unassigned" data-id="0">🔍 Немає не прив'язаних користувачів</li>
         @endif
         {{-- Count of users --}}
      
        {{$users->count() }}
         @foreach ($users_id as $u)
          @php 
            $user = $users->find($u);
          @endphp
            @if ($user->children->isEmpty())
                <li class="node unassigned" data-id="{{ $user->id }}">
                    {{ $user->name }} ({{ $user->roles->first()->name ?? 'Невідомо' }})
                    <ul class="children list-unstyled" data-id="{{ $user->id }}">
                        @foreach ($user->children as $child)
                            @include('users.partials.user-tree', ['userId' => $child->id, 'users' => $users])
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="node unassigned" data-id="{{ $user->id }}">
                    {{ $user->name }} ({{ $user->roles->first()->name ?? 'Невідомо' }})
                </li>
            @endif             
            
        @endforeach
    </ul>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">


<script>
   

    function makeSortable(el) {

         new Sortable(el, {
                group: 'nested',
                animation: 150,
                onAdd: function (evt) {
                    const draggedEl = evt.item;
                    const draggedId = draggedEl.dataset.id;
                    const targetParent = evt.to.closest('.node');
                    const parentId = targetParent?.dataset?.id;
                    console.log(`Зв'язування користувача ${draggedId} з ${parentId}`);
                    
                }
            });
        }
    
    // Ініціалізація всіх існуючих вкладених списків
   document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('ul').forEach(makeSortable);
});
</script>

@endsection

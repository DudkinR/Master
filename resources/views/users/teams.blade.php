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
    <h2>👑 Ієрархія персоналу</h2>
    <ul id="tree" class="list-unstyled">
        <li class="node boss">
            👑 Бос
            <ul class="children list-unstyled">
                <li class="node master">
                    🛠️ Майстер 1
                    <ul class="children list-unstyled">
                        <li class="node brigadier">
                            👷 Бригадир 1
                            <ul class="children list-unstyled">
                                <li class="node worker">🔧 Робітник 1</li>
                                <li class="node worker">🔧 Робітник 2</li>
                            </ul>
                        </li>
                        <li class="node brigadier">👷 Бригадир 2</li>
                    </ul>
                </li>
                <li class="node master">
                    🛠️ Майстер 2
                    <ul class="children list-unstyled">
                        <li class="node brigadier">👷 Бригадир 3</li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>

    <h4>🗃️ Не прив’язані</h4>
    <ul id="unassigned" class="list-unstyled">
        <li class="node worker">🔧 Робітник 3</li>
        <li class="node worker">🔧 Робітник 4</li>
        <li class="node brigadier">👷 Бригадир 4</li>
         @foreach ($users as $user)
            @if ($user->supervisors->isEmpty()) {{-- Тільки кореневі (без керівників) --}}
            @if ($user->roles ->isEmpty())
                <li class="node unassigned">🔍 {{ $user->name }}</li>   
            @elseif ($user->roles->first()->name  === 'Boss')
                    <li class="node boss">👑 {{ $user->name }}</li
                @elseif ($user->roles->first()->name   === 'Master')
                    <li class="node master">🛠️ {{ $user->name }}</li
                @elseif ($user->roles->first()->name   === 'Brigadier')
                    <li class="node brigadier">👷 {{ $user->name }}</li
                @elseif ($user->roles->first()->name   === 'Worker')
                <li class="node worker">🔧 {{ $user->name }}</li>               
                @endif

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

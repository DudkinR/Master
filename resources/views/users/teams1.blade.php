@extends('layouts.app')
@section('title', 'Ієрархія користувачів')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .node {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 8px;
        background-color: #fff;
        cursor: grab;
    }

    .Admin { background-color: #ffcdd2; }
    .Boss { background-color: #c8e6c9; }
    .Engineer { background-color: #d1c4e9; }
    .Master { background-color: #fff59d; }
    .Brigadir { background-color: #81d4fa; }
    .Worker { background-color: #e0e0e0; }
    .Guest { background-color: #f5f5f5; }

    .children {
        margin-left: 20px;
        margin-top: 10px;
    }
</style>

<div class="container my-4">
    <h2>👥 Ієрархія користувачів</h2>

    <ul id="tree" class="list-unstyled">
        @php
            $rendered = [];

            function renderNode($user, &$rendered)
            {
                $rendered[] = $user->id;
                echo '<li class="node '.$user->roles->first()->name.'" data-id="'.$user->id.'">';
                echo '<strong>' . $user->roles->first()->name . '</strong>: ' . $user->name;

                if ($user->children->count()) {
                    echo '<ul class="children list-unstyled">';
                    foreach ($user->children as $child) {
                        renderNode($child, $rendered);
                    }
                    echo '</ul>';
                }

                echo '</li>';
            }
        @endphp

        @foreach ($users as $user)
            @if ($user->supervisors->isEmpty()) {{-- Тільки кореневі (без керівників) --}}
                {!! renderNode($user, $rendered) !!}
            @endif
        @endforeach
    </ul>

    <h4>🗃️ Не прив’язані</h4>
    <ul id="unassigned" class="list-unstyled">
        @foreach ($users as $user)
            @if (!in_array($user->id, $rendered))
                <li class="node {{ $user->roles->first()->name }}" data-id="{{ $user->id }}">
                    <strong>{{ $user->roles->first()->name }}</strong>: {{ $user->name }}
                </li>
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
            const moved = evt.item;
            const targetList = evt.to;
            const parentNode = targetList.closest('li.node');

            if (!parentNode || !moved) return;

            const u1_id = parseInt(parentNode.dataset.id); // новий керівник
            const u2_id = parseInt(moved.dataset.id);      // підлеглий

            if (u1_id && u2_id && u1_id !== u2_id) {
                sendConnection(u1_id, u2_id);
            }

            // Якщо немає <ul class="children"> у цільового елемента, створити її і перемістити туди
            if (!parentNode.querySelector('ul.children')) {
                const ul = document.createElement('ul');
                ul.className = 'children list-unstyled';
                parentNode.appendChild(ul);
                ul.appendChild(moved); // переміщаємо туди
                makeSortable(ul);     // ініціалізація
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('ul').forEach(makeSortable);
});
</script>
@endsection

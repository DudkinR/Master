<div class="d-flex gap-2 mb-4">
    <button id="startBtn" onclick="startWork({{$task->id}})" class="btn btn-success {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? 'd-none' : '' }}">
        ▶ Почати роботу
    </button>
    <button id="pauseBtn" onclick="pauseWork({{$task->id}})" class="btn btn-warning {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? '' : 'd-none' }}">
        ⏸ Перерва
    </button>
    <button id="endBtn" class="btn btn-danger {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? '' : 'd-none' }}"
            data-bs-toggle="modal" data-bs-target="#endWorkModal">
        ⏹ Завершити
    </button>
    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 Проблема</button>
    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓</button>
</div>
    {{-- Таймер --}}
<div class="mb-4">
    <span class="fw-bold">Час у роботі:</span> <span id="timerDisplay" data-starttime="1.6">00:00:00</span>
</div>
    {{-- Таймер трудозатрат --}}
    <style>
    .blink {
    animation: blinker 1s linear infinite;
    }
    @keyframes blinker {
    50% {
        opacity: 0;
        }
    }
    </style>
<div class="mb-4">    
    <span class="fw-bold" id="estimated_hours" data-estimated="0.06">
    Залишок трудозатрат (з {{ $task->estimated_hours }} год):
    </span>
    <span id="worktimerDisplay" class="text-dark fw-bold">00:00:00</span>

</div>
<script>

</script>

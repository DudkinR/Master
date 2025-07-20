<div class="d-flex gap-2 mb-4">
    <button id="pauseBtn" onclick="pauseWork({{ $task->id }})" class="btn btn-warning {{ $task->status === 'in_progress' ? '' : 'd-none' }}">
        ⏸ Перерва
    </button>
    <button id="endBtn" class="btn btn-danger {{ $task->status === 'in_progress' ? '' : 'd-none' }}"
            data-bs-toggle="modal" data-bs-target="#endWorkModal">
        ⏹ Завершити
    </button>
    <button id="startBtn" onclick="startWork({{ $task->id }})" class="btn btn-success {{ $task->status === 'in_progress' ? 'd-none' : '' }}">
        ▶ Почати роботу
    </button>
    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 Проблема</button>
    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓</button>
</div>

{{-- Таймер --}}
<div class="mb-4">
    <span class="fw-bold">Час у роботі:</span>
    <span id="timerDisplay" data-starttime="{{ $timeSpent ?? 0 }}">00:00:00</span>
</div>

{{-- Таймер трудозатрат --}}
<style>
.blink {
    animation: blinker 1s linear infinite;
}
@keyframes blinker {
    50% { opacity: 0; }
}
</style>

<div class="mb-4">    
    <span class="fw-bold" id="estimated_hours" data-estimated="{{ $task->estimated_hours }}">
        Залишок трудозатрат (з {{ $task->estimated_hours }} год):
    </span>
    <span id="worktimerDisplay" class="text-dark fw-bold">00:00:00</span>
</div>


<script>
const task = @json($task);

function updateButtons(status) {
    const pauseBtn = document.getElementById('pauseBtn');
    const endBtn = document.getElementById('endBtn');
    const startBtn = document.getElementById('startBtn');

    if (status === "in_progress") {
        pauseBtn.classList.remove('d-none');
        endBtn.classList.remove('d-none');
        startBtn.classList.add('d-none');
    } else if (status === "paused") {
        pauseBtn.classList.add('d-none');
        endBtn.classList.add('d-none');
        startBtn.classList.remove('d-none');
    }
}

function startTimer() {
    clearInterval(window.timerInterval);
    const displayElement = document.getElementById('timerDisplay');
    const previouslySpentHours = parseFloat(displayElement.dataset.starttime || '0');
    const previouslySpentMs = previouslySpentHours * 60 * 60 * 1000;
    const startTime = new Date();

    window.timerInterval = setInterval(() => {
        const elapsedMs = new Date() - startTime + previouslySpentMs;
        const t = new Date(elapsedMs);
        displayElement.textContent = `${String(t.getUTCHours()).padStart(2, '0')}:${String(t.getUTCMinutes()).padStart(2, '0')}:${String(t.getUTCSeconds()).padStart(2, '0')}`;
    }, 1000);
}

function pauseTimer() {
    const displayElement = document.getElementById('timerDisplay');
    const [h, m, s] = displayElement.textContent.split(':').map(Number);
    const elapsedHours = (h * 3600 + m * 60 + s) / 3600;
    displayElement.dataset.starttime = elapsedHours.toFixed(2);
    clearInterval(window.timerInterval);
}

function startTimerWork() {
    clearInterval(window.workTimerInterval);

    const startTime = new Date();
    const workersCount = countWorkers() || 1;
    const estimatedElement = document.getElementById('estimated_hours');
    const estimatedHours = parseFloat(estimatedElement.dataset.estimated || '0');
    const totalWorkMs = estimatedHours * 3600 * 1000;
    const displayElement = document.getElementById('worktimerDisplay');

    window.workTimerInterval = setInterval(() => {
        const elapsedMs = (new Date() - startTime) * workersCount;
        const remainingMs = totalWorkMs - elapsedMs;

        if (remainingMs <= 0) {
            clearInterval(window.workTimerInterval);
            displayElement.textContent = "00:00:00";
            displayElement.classList.replace('text-dark', 'text-danger');
            displayElement.classList.add('blink');
            return;
        }

        const t = new Date(remainingMs);
        displayElement.textContent = `${String(t.getUTCHours()).padStart(2, '0')}:${String(t.getUTCMinutes()).padStart(2, '0')}:${String(t.getUTCSeconds()).padStart(2, '0')}`;
        displayElement.classList.remove('text-danger', 'blink');
        displayElement.classList.add('text-dark');
    }, 1000);
}

function pauseTimerWork() {
    const displayElement = document.getElementById('worktimerDisplay');
    const [h, m, s] = displayElement.textContent.split(':').map(Number);
    const elapsedHours = (h * 3600 + m * 60 + s) / 3600;
    displayElement.dataset.starttime = elapsedHours.toFixed(2);
    clearInterval(window.workTimerInterval);
}

function countWorkers() {
    return document.querySelectorAll('input[name="worker[]"]:checked').length;
}

document.addEventListener('DOMContentLoaded', function () {
    updateButtons(task.status);
    
    const timeSpent = parseFloat(document.getElementById('timerDisplay').dataset.starttime || '0');
    if (task.status === 'in_progress' && timeSpent > 0) {
        startTimer();
        startTimerWork();
    }
});
</script>

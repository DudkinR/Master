 <div class="mb-3">
        <label class="form-label fw-bold">Наявність техніки:</label><br>
        @foreach ($task->machines as $machine)
            <div class="form-check form-check-inline">              
                <label class="form-check-label" for="machine_{{$machine->id}}">{{ $machine->name }}</label>
                {{-- buttons для техніки  початок роботи, перерва, закінчення таймер роботи --}}
                <button id="start_machine_{{$machine->id}}" 
                 class="btn btn-outline-secondary btn-sm" onClick="startMachine({{ $machine->id }} ,{{ $task->id }})">
                    ▶ Почати роботу
                </button>
                <button id="pause_machine_{{$machine->id}}"
                 class="btn btn-outline-warning btn-sm d-none" onClick="pauseMachine({{ $machine->id }},{{ $task->id }})">
                    ⏸ Перерва
                </button>
                <button id="end_machine_{{$machine->id}}"
                 class="btn btn-outline-danger btn-sm d-none" onClick="endMachine({{ $machine->id }},{{ $task->id }})">
                    ⏹ Завершити
                </button>
                {{-- Timer --}} 
                <span class="badge bg-info ms-2" id="machineTimerDisplay-{{ $machine->id }}">00:00:00</span>
                
            </div>
        @endforeach
</div>
<button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createTechnicModal-{{ $task->id }}">
    ➕ Додати техніку
</button>
<div class="modal fade" id="createTechnicModal-{{ $task->id }}" tabindex="-1" aria-labelledby="technicModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('machines.store') }}" method="POST">
                @csrf
                <input type="hidden" name="views" value="showtask">
                <input type="hidden" name="task_id" value="{{ $task->id }}">
               
                <div class="modal-header">
                    <h5 class="modal-title" id="technicModalLabel-{{ $task->id }}">Нова техніка</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Назва" required>
                        <label for="name">Назва</label>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control" id="description" name="description" placeholder="Опис" style="height: 80px;" required></textarea>
                        <label for="description">Опис</label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="unit" placeholder="Одиниця" required  value="шт">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100">💾 Зберегти</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
     // Об'єкт для зберігання інтервалів
    const machineTimers = {};

function startMachineTimer(machineId) {
    const displayElement = document.getElementById(`machineTimerDisplay-${machineId}`);
    // Якщо таймер уже йде — не запускати знову
    if (machineTimers[machineId]?.interval) return;
    // Якщо вже був накопичений час — використовуємо, інакше 0
    const previousMs = machineTimers[machineId]?.elapsedMs || 0;
    const startTime = new Date();
    // Запуск таймера
    const interval = setInterval(() => {
        const currentTime = new Date();
        const elapsedMs = previousMs + (currentTime - startTime);
        // Зберігаємо проміжний час
        machineTimers[machineId].elapsedMs = elapsedMs;
        const elapsedTime = new Date(elapsedMs);
        const hours = String(elapsedTime.getUTCHours()).padStart(2, '0');
        const minutes = String(elapsedTime.getUTCMinutes()).padStart(2, '0');
        const seconds = String(elapsedTime.getUTCSeconds()).padStart(2, '0');
        displayElement.textContent = `${hours}:${minutes}:${seconds}`;
    }, 1000);
    // Зберігаємо у глобальний об'єкт
    machineTimers[machineId] = {
        startTime,
        interval,
        elapsedMs: previousMs
    };
}

function pauseMachineTimer(machineId) {
    const displayElement = document.getElementById(`machineTimerDisplay-${machineId}`);

    const timer = machineTimers[machineId];
    if (timer && timer.interval) {
        clearInterval(timer.interval);
        delete machineTimers[machineId].interval;

        displayElement.textContent += ' (Пауза)';
    }
}

function startMachine(machineId,taskId) {
    const startButton = document.getElementById(`start_machine_${machineId}`);
    const pauseButton = document.getElementById(`pause_machine_${machineId}`);
    const endButton = document.getElementById(`end_machine_${machineId}`);
    startButton.classList.add('d-none');
    pauseButton.classList.remove('d-none');
    endButton.classList.remove('d-none');
    // Зібрати дані з форми 
    const bodyData = {
        task_id: taskId,
        start_time: new Date().toISOString(),
        machine_id: machineId,
        action_type: 'start',           
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('machines.start', $task->id) }}`, bodyData);
    // Запустити таймер для техніки
    startMachineTimer(machineId);
}

function pauseMachine(machineId) {
    const displayElement = document.getElementById(`machineTimerDisplay-${machineId}`);
    const pauseButton = document.getElementById(`pause_machine_${machineId}`);
    const startButton = document.getElementById(`start_machine_${machineId}`);
    const endButton = document.getElementById(`end_machine_${machineId}`);
    pauseButton.classList.add('d-none');    
    startButton.classList.remove('d-none');
    endButton.classList.add('d-none');
    // Зібрати дані з форми
    const bodyData = {
        action_type: 'pause',
        start_time: new Date().toISOString(),
        machine_id: machineId,
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('machines.pause', $task->id) }}`, bodyData);
    // Зупинити таймер для техніки
    pauseMachineTimer(machineId);
}

function endMachine(machineId, taskId) {
    const endButton = document.getElementById(`end_machine_${machineId}`);
    const pauseButton = document.getElementById(`pause_machine_${machineId}`);
    const startButton = document.getElementById(`start_machine_${machineId}`);
    endButton.classList.add('d-none');
    pauseButton.classList.add('d-none');
    // add lable відпрацівала
    startButton.classList.add('d-none');
    const displayElement = document.getElementById(`machineTimerDisplay-${machineId}`);
    displayElement.textContent += ' (Відпрацювала)';
    // Змінити текст кнопки на "Відпрацювала"
    startButton.textContent = 'Відпрацювала';
    startButton.classList.add('btn-success');
    startButton.classList.remove('btn-outline-secondary');
    startButton.disabled = true; // Заборонити повторний запуск
    // Зібрати дані з форми
    const bodyData = {
        task_id: taskId,
        action_type: 'end',
        end_time: new Date().toISOString(),
        machine_id: machineId,
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('machines.end', $task->id) }}`, bodyData);
    // Зупинити таймер для техніки  
    pauseMachineTimer(machineId);
}



    document.addEventListener('DOMContentLoaded', function() {
        // Ініціалізація таймерів для кожної техніки
        @foreach ($task->machines as $machine)
        // Запуск таймера якщо техніка вже працює
            @if ($machine->is_working)
            startMachineTimer({{ $machine->id }});
            @endif
        @endforeach
    });

  

    function senddata(url, bodyData) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: bodyData ? JSON.stringify(bodyData) : null
        })
        .then(response => response.json())
        .then(data => {            
            console.log(data);            
        })
        .catch(err => {
            console.log(err + 'Помилка мережі або сервера.');
        });
}
</script>
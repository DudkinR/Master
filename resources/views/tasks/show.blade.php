@extends('layouts.app')
@section('title', 'Картка завдання')
@section('content')
<div class="container my-5">
    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif
    <h3 class="mb-4">Картка завдання: <strong>{{ $task->name }}</strong></h3>
    <!-- Кнопки управління -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        {{-- Статус завдання --}}
        @if ($task->status === 'completed')
            <span class="badge bg-success">✅ Завершено</span>
        @elseif ($task->status === 'in_progress')
            <span class="badge bg-warning text-dark">🛠 У процесі</span>
             <button id="endBtn" class="btn btn-danger {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? '' : 'd-none' }}"
                data-bs-toggle="modal" data-bs-target="#endWorkModal">
                    ⏹ Завершити
            </button>
        @else
            <span class="badge bg-secondary">🕒 Очікує</span>
        @endif
        {{-- Остання дія користувача --}}
        @if ($lastAction)
            @php
                $actionLabels = [
                    'start' => 'Початок',
                    'pause' => 'Перерва',
                    'resume' => 'Продовження',
                    'end_day' => 'Завершення дня',
                    'complete' => 'Завершено',
                    'issue' => 'Проблема',
                ];
            @endphp
        @else
            <span class="badge bg-secondary">❌ Ще не розпочато</span>
        @endif
    </div>
    {{-- Кнопки дій --}}
    <div class="d-flex gap-2 mb-4">
        <button id="startBtn" class="btn btn-success {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? 'd-none' : '' }}">
            ▶ Почати роботу
        </button>
        <button id="pauseBtn" class="btn btn-warning {{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? '' : 'd-none' }}">
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
        <span class="fw-bold">Час у роботі:</span> <span id="timerDisplay">00:00:00</span>
    </div>
    {{-- Таймер трудозатрат --}}
    <div class="mb-4">
        <span class="fw-bold">Залишок трудозатрат (з {{ $task->estimated_hours }} год):</span>
        <span id="worktimerDisplay">00:00:00</span>
    </div>


    <!-- Персонал -->
    <div class="mb-3">
        <label class="form-label fw-bold">Персонал на об'єкті:</label><br>
        @foreach ($task->users()->wherePivot('role', '!=', 'master')->get() as $user)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="worker_{{$user->id}}" checked>
                <label class="form-check-label" for="worker_{{$user->id}}">{{ $user->name }}</label>
            </div>
        @endforeach
    </div>

    <!-- ТМЦ -->
    <div class="mb-3">
        <label class="form-label fw-bold">Наявність ТМЦ:</label><br>
        @foreach ($task->materials as $material)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="material_{{$material->id}}" checked>
                <label class="form-check-label" for="material_{{$material->id}}">{{ $material->name }}</label>
            </div>
        @endforeach
        
    </div>
 <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
            ➕ Додати матеріал               </button>
            <!-- Модальне вікно -->
            <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('materials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="views" value="showtask">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $task->id }}">Новий матеріал</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Назва" required>
                                    <label for="name">Назва</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="Опис" style="height: 80px" required></textarea>
                                    <label for="description">Опис</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
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
    <!-- Обладнання -->
    <div class="mb-3">
        <label class="form-label fw-bold">Наявність обладнання:</label><br>
        @foreach ($task->equipments as $equipment)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="equipment_{{$equipment->id}}" checked>
                <label class="form-check-label" for="equipment_{{$equipment->id}}">{{ $equipment->name }}</label>
            </div>
        @endforeach
    </div>
    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createEquipmentModal-{{ $task->id }}">
                ➕ Додати обладнання
            </button>

            <div class="modal fade" id="createEquipmentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="equipmentModalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('equipments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="views" value="showtask">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="equipmentModalLabel-{{ $task->id }}">Нове обладнання</h5>
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
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
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
    <!-- техника -->
    <div class="mb-3">
        <label class="form-label fw-bold">Наявність техніки:</label><br>
        @foreach ($task->machines as $machine)
            <div class="form-check form-check-inline">              
                <label class="form-check-label" for="machine_{{$machine->id}}">{{ $machine->name }}</label>
                {{-- buttons для техніки  початок роботи, перерва, закінчення таймер роботи --}}
                <button class="btn btn-outline-secondary btn-sm" >
                    ▶ Почати роботу
                </button>
                <button class="btn btn-outline-warning btn-sm d-none" >
                    ⏸ Перерва
                </button>
                <button class="btn btn-outline-danger btn-sm d-none">
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
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
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
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
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

    <!-- Модальне завершення роботи -->
    <div class="modal fade" id="endWorkModal" tabindex="-1" aria-labelledby="endWorkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tasks.complete', $task->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Завершити роботу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="progress" class="form-label">Виконано, %</label>
                    <input type="number" name="progress" class="form-control" min="0" max="100" required>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="photo_report" id="photoReport">
                        <label class="form-check-label" for="photoReport">Додати фото-звіт</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Підтвердити завершення</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Проблема -->
    <div class="modal fade" id="problemModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tasks.report_issue', $task->id) }}">
                @csrf
                <input type="hidden" name="views" value="showtask">
                <input type="hidden" name="task_id" value="{{ $task->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Проблема</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" name="title" placeholder="Заголовок проблеми" required>
                        <label for="title">Заголовок проблеми</label>
                    </div>
                    <label for="description" class="form-label">Опис проблеми</label>
                    <textarea class="form-control" name="description"  placeholder="Опишіть проблему..." rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Надіслати</button>
                </div>
                <div class="modal-footer">
                    
                    @foreach ($problems as $problem)
                        <div class="alert alert-danger mb-2">
                            <h6 class="alert-heading">{{ $problem->title }}</h6>
                            <p>{{ $problem->description }}</p>
                        </div>
                    @endforeach
                    @if ($problems->isEmpty())
                        <div class="alert alert-info">Проблем немає.</div>
                    @endif

                </div>
            </form>
        </div>
    </div>

    <!-- Довідка -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Довідка</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><strong>▶ Почати роботу</strong> — запускає таймер і фіксує початок.</li>
                        <li><strong>⏸ Перерва</strong> — фіксує зупинку без завершення.</li>
                        <li><strong>⏹ Завершити</strong> — заповнення % і фото.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let timerInterval;
    let seconds = 0;
    const estimatedSeconds = {{ $task->estimated_hours * 3600 }};
    const activeWorkerCount = {{ $task->users()->wherePivot('role', '!=', 'master')->count() }};

    const display = document.getElementById('timerDisplay');
    const workDisplay = document.getElementById('worktimerDisplay');
      // Якщо остання дія була "start" або "resume", продовжуємо відлік
    @if ($lastAction && in_array($lastAction->action_type, ['start', 'resume']))
        const startedAt = new Date("{{ $lastAction->created_at->toIso8601String() }}");
        const now = new Date();
        seconds = Math.floor((now - startedAt) / 1000);
        startTimer();
    @endif           
    function formatTime(s) {
        const h = String(Math.floor(s / 3600)).padStart(2, '0');
        const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
        const sec = String(s % 60).padStart(2, '0');
        return `${h}:${m}:${sec}`;
    }
    function startTimer() {
        display.textContent = formatTime(seconds);

        timerInterval = setInterval(() => {
            seconds++;
            display.textContent = formatTime(seconds);

            // Обчислення фактичних витрачених людино-секунд
            const usedSeconds = seconds * activeWorkerCount;

            // Обрахунок залишку
            const remaining = estimatedSeconds - usedSeconds;

            // Якщо залишилось часу
            if (remaining > 0) {
                workDisplay.textContent = formatTime(remaining);
                workDisplay.classList.remove('text-danger');
            } else {
                workDisplay.textContent = formatTime(0);
                workDisplay.classList.add('text-danger');
            }

        }, 1000);
    }

document.getElementById('startBtn').addEventListener('click', () => {
    const workerIds = Array.from(document.querySelectorAll('input[id^="worker_"]:checked'))
                            .map(checkbox => checkbox.id.replace('worker_', ''));
    const materialIds = Array.from(document.querySelectorAll('input[id^="material_"]:checked'))
                            .map(checkbox => checkbox.id.replace('material_', ''));
    const equipmentIds = Array.from(document.querySelectorAll('input[id^="equipment_"]:checked'))
                            .map(checkbox => checkbox.id.replace('equipment_', ''));
    fetch(`{{ route('tasks.start', $task->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            start_time: new Date().toISOString(),
            worker_ids: workerIds,
            material_ids: materialIds,
            equipment_ids: equipmentIds,            
            location: null // або додайте GPS через navigator.geolocation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Помилка при запуску роботи: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Помилка мережі або сервера.');
        });

        // запуск таймера
        timerInterval = setInterval(() => {
            seconds++;
            display.textContent = formatTime(seconds);

        }, 1000);
        // запуск таймера трудозатрат
        workDisplay.textContent = formatTime(estimatedSeconds);
        workDisplay.classList.remove('text-danger');
        // запуск таймера трудозатрат
        startTimer();


        // зміна кнопок
        document.getElementById('startBtn').classList.add('d-none');
        document.getElementById('pauseBtn').classList.remove('d-none');
        document.getElementById('endBtn').classList.remove('d-none');
    });


    document.getElementById('pauseBtn').addEventListener('click', () => {
         const workerIds = Array.from(document.querySelectorAll('input[id^="worker_"]:checked'))
                            .map(checkbox => checkbox.id.replace('worker_', ''));
        fetch(`{{ route('tasks.pause', $task->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pause_time: new Date().toISOString(),
                worker_ids: workerIds,                              
                location: null // або додайте GPS через navigator.geolocation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Помилка при паузі: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Помилка мережі або сервера.');
        });

        // зупинка таймерів
        clearInterval(timerInterval);
        seconds = 0;
        display.textContent = formatTime(seconds);
        workDisplay.textContent = formatTime(estimatedSeconds);
        workDisplay.classList.remove('text-danger');
        

        // зміна кнопок
        document.getElementById('startBtn').classList.remove('d-none');
        document.getElementById('pauseBtn').classList.add('d-none');
        document.getElementById('endBtn').classList.add('d-none');
    });

    document.getElementById('endBtn').addEventListener('click', () => {
        const workerIds = Array.from(document.querySelectorAll('input[id^="worker_"]:checked'))
                            .map(checkbox => checkbox.id.replace('worker_', ''));

        fetch(`{{ route('tasks.end', $task->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                end_time: new Date().toISOString(),
                worker_ids: workerIds,
                location: null // або додайте GPS через navigator.geolocation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload(); // перезавантаження сторінки для оновлення даних
            } else {
                alert('Помилка при завершенні роботи: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Помилка мережі або сервера.');
        });

        // зупинка таймерів
        clearInterval(timerInterval);
        seconds = 0;
        display.textContent = formatTime(seconds);
        workDisplay.textContent = formatTime(estimatedSeconds);
        workDisplay.classList.remove('text-danger');

        // зміна кнопок
        document.getElementById('startBtn').classList.remove('d-none');
        document.getElementById('pauseBtn').classList.add('d-none');
        document.getElementById('endBtn').classList.add('d-none');
    });

    // Обробка кнопок техніки
    document.querySelectorAll('.btn-outline-secondary').forEach(button => {
        button.addEventListener('click', (e) => {
            const machineId = e.target.closest('.form-check').querySelector('label').getAttribute('for').replace('machine_', '');
            const action = e.target.textContent.trim();

            if (action === '▶ Почати роботу') {
                // Логіка для початку роботи з технікою
                e.target.classList.add('d-none');
                e.target.nextElementSibling.classList.remove('d-none'); // показати кнопку перерви
                e.target.nextElementSibling.nextElementSibling.classList.remove('d-none'); // показати кнопку завершення
                document.getElementById(`machineTimerDisplay-${machineId}`).textContent = '00:00:00'; // скидання таймера
            } else if (action === '⏸ Перерва') {
                // Логіка для перерви з технікою
                e.target.classList.add('d-none');
                e.target.nextElementSibling.classList.remove('d-none'); // показати кнопку завершення
            } else if (action === '⏹ Завершити') {
                // Логіка для завершення роботи з технікою
                e.target.classList.add('d-none');
                e.target.previousElementSibling.classList.remove('d-none'); // показати кнопку початку роботи
            }
            // timer для техніки
            let machineSeconds = 0;
            const machineTimerDisplay = document.getElementById(`machineTimerDisplay-${machineId}`);
            const machineTimerInterval = setInterval(() => {
                machineSeconds++;
                machineTimerDisplay.textContent = formatTime(machineSeconds);
            }, 1000);
            // Зупинка таймера при завершенні роботи
            e.target.closest('.form-check').querySelectorAll('.btn-outline-danger').forEach(endButton => {
                endButton.addEventListener('click', () => {
                    clearInterval(machineTimerInterval);
                    machineSeconds = 0;
                    machineTimerDisplay.textContent = '00:00:00';
                    endButton.classList.add('d-none');
                    endButton.previousElementSibling.classList.remove('d-none'); // показати кнопку початку роботи
                });
            });
            // ajax запит для початку роботи з технікою
            fetch(`{{ route('machines.start', ['id' => $task->id]) }}${machineId}`, {
                method: 'POST',
                 headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: action.toLowerCase(),
                machine_id: machineId,
                task_id: {{ $task->id }},
                project_id: {{ $task->projects->first()->id }},
                location: null // або додайте GPS через navigator.geolocation
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                } else {
                    alert('Помилка при роботі з технікою: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Помилка мережі або сервера.');
            });
        });
    });

</script>
@endsection
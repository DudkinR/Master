<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
 const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
 console.log('CSRF Token:', csrfToken);
 const taskId= '{{ $task->id }}'; 
function senddata(url, bodyData) {
        // add CSRF token to the bodyData
    bodyData._token = csrfToken;
    fetch(url, {
        method: 'POST',
        headers: {
           // відправляю форму enctype="multipart/form-data"
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Cache-Control': 'no-cache',
            'Pragma': 'no-cache',
            'Content-Type': 'application/json',           
            'X-CSRF-TOKEN': csrfToken
            
        },
        body: JSON.stringify(bodyData)
    })    
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Дані успішно надіслані:', data);
        } else {
            console.error('Помилка відповіді:', data);
        }
    })
    .catch(error => console.error('Fetch помилка:', error));
}     
function  startWork() { 
   
    const pauseBtn = document.getElementById('pauseBtn');
    const endBtn = document.getElementById('endBtn');
     const startBtn = document.getElementById('startBtn');
    // Сховати кнопку "Почати роботу"
    startBtn.classList.add('d-none');    
    // Показати кнопки "Перерва" та "Завершити"
    pauseBtn.classList.remove('d-none');
    endBtn.classList.remove('d-none');    
    // Зібрати дані з форми
    const bodyData = {
            task_id: taskId,
            _token: csrfToken,
          start_time: new Date().toISOString(),
          worker_ids: Array.from(document.querySelectorAll('input[name="worker[]"]:checked'))
                .map(checkbox => checkbox.id.replace('worker_', '')),
          material_ids: Array.from(document.querySelectorAll('input[name="material[]"]:checked'))
                .map(checkbox => checkbox.id.replace('material_', '')),
          equipment_ids: Array.from(document.querySelectorAll('input[name="equipment[]"]:checked'))
                .map(checkbox => checkbox.id.replace('equipment_', '')),
          action_type: 'start',      
          location: getCoordinatesString() // або додайте GPS через navigator.geolocation
     };
    // Відправити дані на сервер
    senddata(`{{ route('tasks.start') }}`, bodyData);
    // Запустити таймер
   startTimer();
   startTimerWork();
    // Оновити статус завдання
    updateTaskStatus('in_progress');
}
function pauseWork() {
    // знайти кнопки у яких  id починаються "pause_machine_" і немає класу 'd-none'
    const pauseButtons = document.querySelectorAll('button[id^="pause_machine_"]:not(.d-none)');
    // якщо є кнопки з id починаються "pause_machine_" і немає класу 'd-none'
    if (pauseButtons.length > 0) {  
        // перебрати всі кнопки і викликати pauseMachine для кожної
        pauseButtons.forEach(button => {
            const machineId = button.id.replace('pause_machine_', '');
            pauseMachine(machineId, taskId);
        });
    }

    const pauseBtn = document.getElementById('pauseBtn');
    const startBtn = document.getElementById('startBtn');
    const endBtn = document.getElementById('endBtn');
    // Сховати кнопку "Перерва"
    pauseBtn.classList.add('d-none');
    // Показати кнопку "Почати роботу"
    startBtn.classList.remove('d-none');
    // Сховати кнопку "Завершити"
    endBtn.classList.add('d-none');
    // Зібрати дані з форми
    const bodyData = {
        _token: csrfToken,
        action_type: 'pause',
        start_time: new Date().toISOString(),
        worker_ids: Array.from(document.querySelectorAll('input[name="worker[]"]:checked'))
            .map(checkbox => checkbox.id.replace('worker_', '')),
        material_ids: Array.from(document.querySelectorAll('input[name="material[]"]:checked'))
            .map(checkbox => checkbox.id.replace('material_', '')),
        equipment_ids: Array.from(document.querySelectorAll('input[name="equipment[]"]:checked'))
            .map(checkbox => checkbox.id.replace('equipment_', '')),
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('tasks.pause', $task->id) }}`, bodyData);
    // Зупинити таймер
    pauseTimer();
    pauseTimerWork();
    updateTaskStatus('paused');
}

function getCoordinatesString() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) {
            resolve(null);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                resolve(`${lat}, ${lon}`);
            },
            function (error) {
                // У разі будь-якої помилки — повернути null
                resolve(null);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    });
}
const modal = new bootstrap.Modal(document.getElementById('endWorkModal'));
document.addEventListener('DOMContentLoaded', function () {
    // 1. Прив'язуємо кнопку до машин (не змінюється)
    const endButtons = document.querySelectorAll('button[id^="end_machine_"]:not(.d-none)');
    if (endButtons.length > 0) {
        endButtons.forEach(button => {
            const machineId = button.id.replace('end_machine_', '');
            button.addEventListener('click', function () {
                endMachine(machineId, taskId);
            });
        });
    }

    // 2. Обробка форми
    const form = document.getElementById('endWorkForm');
    form.addEventListener('submit', function (event) {
        const progressValue = parseInt(document.getElementById('progress').value);
        const endDayValue = document.querySelector('input[name="end_day"]:checked').value;

        // 2.1. Перевірка умови перед відправкою
        if (progressValue === 100 && endDayValue !== 'completed') {
            event.preventDefault(); // Блокуємо стандартну відправку

            if (confirm('Завершити робочий день повністю?')) {
                document.getElementById('end_day2').checked = true;
            } else {
                document.getElementById('end_day1').checked = true;
                document.getElementById('progress').value = 99;
            }

            // Після ручної зміни значень – викликаємо submit знову
            form.requestSubmit();
            return; // Вийти, щоб уникнути подвійного надсилання
        }

        // 2.2. Якщо все гаразд — можна надіслати форму через fetch (якщо розкоментуєте)

        event.preventDefault(); // Знову блокуємо стандартну поведінку
        const url = "{{ route('tasks.complete', $task->id) }}";
        const formData = new FormData(form);
        formData.append('task_id', '{{ $task->id }}');
        formData.append('_token', '{{ csrf_token() }}');
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                pauseTimer();
                pauseTimerWork();
                document.getElementById('pauseBtn').classList.add('d-none');
                document.getElementById('endBtn').classList.add('d-none');
                document.getElementById('startBtn').classList.remove('d-none');
                window.location.href = "{{ route('tasks.index') }}";
            } else {
                console.error('Помилка відповіді:', data);
            }
        })
        .catch(error => console.error('Fetch помилка:', error));
       
    });
});

function updateTaskStatus(status) {
   const taskData = document.getElementById('taskData');
    const taskStatus = document.getElementById('taskStatus');
    const lastActionStatus = document.getElementById('lastActionStatus');
    taskData.dataset.status = status || taskData.dataset.status;
    const lastAction = taskData.dataset.lastAction;
    const statusMap = {
        completed: { text: '✅ Завершено', class: 'bg-success' },
        in_progress: { text: '🛠 У процесі', class: 'bg-warning text-dark' },
        paused: { text: '⏸ На паузі', class: 'bg-info text-dark' },
        start_day: { text: '▶ Розпочато день', class: 'bg-primary' },
        end_day: { text: '⏹ Завершено день', class: 'bg-secondary' },
        default: { text: '🕒 Очікує', class: 'bg-secondary' }
    };
    const actionMap = {
        start: 'Початок',
        pause: 'Перерва',
        resume: 'Продовження',
        end_day: 'Завершення дня',
        start_day: 'Початок дня',
        complete: 'Завершено',
        issue: 'Проблема'
    };
    // Виведення статусу завдання
    const currentStatus = statusMap[status] || statusMap['default'];
    taskStatus.textContent = currentStatus.text;
    taskStatus.className = `badge w-100 ${currentStatus.class}`;
    // Виведення останньої дії
    if (lastAction) {
        lastActionStatus.textContent = actionMap[lastAction] ?? 'Невідома дія';
        lastActionStatus.className = 'badge bg-dark w-100';
    } else {
        lastActionStatus.textContent = '❌ Ще не розпочато';
        lastActionStatus.className = 'badge bg-secondary w-100';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateTaskStatus({{ $task->status }});
});

// Функція ресайзу зображення
function resizeImage(file, maxWidth, maxHeight) {
    return new Promise(resolve => {
        const img = new Image();
        const reader = new FileReader();

        reader.onload = function (e) {
            img.src = e.target.result;
        };

        img.onload = function () {
            let width = img.width;
            let height = img.height;

            // Масштабування зображення
            if (width > maxWidth || height > maxHeight) {
                const scale = Math.min(maxWidth / width, maxHeight / height);
                width = width * scale;
                height = height * scale;
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(blob => {
                const resizedFile = new File([blob], file.name, {
                    type: file.type,
                    lastModified: Date.now()
                });
                resolve(resizedFile);
            }, file.type, 0.8); // 0.8 - якість JPEG (для стиснення)
        };

        reader.readAsDataURL(file);
    });
}

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
     const starWtBtn = document.getElementById('startBtn');
    // якщо  starWtBtn "Почати роботу" має клас  ('d-none') ещ запускаємо startwork
    if (!starWtBtn.classList.contains('d-none')) {
        startWork(taskId);
    }     
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
        action_type: 'machine_start',
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('machines.start') }}`, bodyData);
    // Запустити таймер для техніки
    startMachineTimer(machineId);
}

function pauseMachine(machineId, taskId) {
    const displayElement = document.getElementById(`machineTimerDisplay-${machineId}`);
    const pauseButton = document.getElementById(`pause_machine_${machineId}`);
    const startButton = document.getElementById(`start_machine_${machineId}`);
    const endButton = document.getElementById(`end_machine_${machineId}`);
    pauseButton.classList.add('d-none');    
    startButton.classList.remove('d-none');
    endButton.classList.add('d-none');
    // Зібрати дані з форми
    const bodyData = {
        task_id: taskId,
        action_type: 'machine_pause',
        start_time: new Date().toISOString(),
        machine_id: machineId,
        location: getCoordinatesString() // або додайте GPS через navigator.geolocation
    };
    // Відправити дані на сервер
    senddata(`{{ route('machines.pause') }}`, bodyData);
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
    senddata(`{{ route('machines.end') }}`, bodyData);
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
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
 const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
 console.log('CSRF Token:', csrfToken);
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
function  startWork(taskId) { 
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const endBtn = document.getElementById('endBtn');
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
    const form = document.getElementById('endWorkForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // блокуємо стандартну поведінку

        const url = "{{ route('tasks.complete', $task->id) }}";
        const formData = new FormData(form);
        formData.append('task_id', '{{ $task->id }}');
        // Додаємо CSRF токен
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
                console.log(data);
                // Optional: Закрити модальне вікно та оновити сторінку
                modal.hide();
                // остановити таймери, сховати кнопки перерва та завершення показати кнопку почати роботу
                pauseTimer();
                pauseTimerWork();
                document.getElementById('pauseBtn').classList.add('d-none');
                document.getElementById('endBtn').classList.add('d-none');
                document.getElementById('startBtn').classList.remove('d-none');
                // relocated route tasks.index
                alert('Робочий день завершено!');
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
</script>
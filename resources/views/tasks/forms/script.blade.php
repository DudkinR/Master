<script>
    
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
    senddata(`{{ route('tasks.start', $task->id) }}`, bodyData);
    // Запустити таймер
   startTimer();
   startTimerWork();
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
}
function startTimer() {
    const displayElement = document.getElementById('timerDisplay');

    // Поточний час старту
    const startTime = new Date();

    // Зчитати скільки вже витрачено годин
    const previouslySpentHours = parseFloat(displayElement.dataset.starttime || '0');
    const previouslySpentMs = previouslySpentHours * 60 * 60 * 1000; // години → мілісекунди

    // Оновлення кожну секунду
    const timerInterval = setInterval(() => {
        const currentTime = new Date();
        const elapsedMs = currentTime - startTime + previouslySpentMs;

        const elapsedTime = new Date(elapsedMs);

        const hours = String(elapsedTime.getUTCHours()).padStart(2, '0');
        const minutes = String(elapsedTime.getUTCMinutes()).padStart(2, '0');
        const seconds = String(elapsedTime.getUTCSeconds()).padStart(2, '0');

        displayElement.textContent = `${hours}:${minutes}:${seconds}`;
    }, 1000);

    window.timerInterval = timerInterval;
}
function pauseTimer() {
    const displayElement = document.getElementById('timerDisplay');
    const elapsedTime = displayElement.textContent.split(':').map(Number);
    const elapsedMilliseconds = (elapsedTime[0] * 3600 + elapsedTime[1] * 60 + elapsedTime[2]) * 1000;
    // Зберегти час у мілісекундах
    displayElement.dataset.starttime = (elapsedMilliseconds / 1000 / 60 / 60).toFixed(2); // зберігаємо в годинах
    // Очистити інтервал
    clearInterval(window.timerInterval);
}

function startTimerWork() {
    const startTime = new Date();
    const workersCount = countWorkers();
    const estimatedElement = document.getElementById('estimated_hours');
    const estimatedHours = parseFloat(estimatedElement.dataset.estimated || '0');
    const totalWorkMilliseconds = estimatedHours * 60 * 60 * 1000;
    const displayElement = document.getElementById('worktimerDisplay');
    const workTimerInterval = setInterval(() => {
        const currentTime = new Date();
        const elapsedMilliseconds = (currentTime - startTime) * workersCount;
        const remainingMilliseconds = totalWorkMilliseconds - elapsedMilliseconds;
        if (remainingMilliseconds <= 0) {
            // Зупинити інтервал
            clearInterval(workTimerInterval);
            // Показати 00:00:00 + червоний + миготіння
            displayElement.textContent = "00:00:00";
            displayElement.classList.remove('text-dark');
            displayElement.classList.add('text-danger', 'blink');
            return; // обов'язково — вихід з функції
        }
        const remainingTime = new Date(remainingMilliseconds);
        const hours = String(remainingTime.getUTCHours()).padStart(2, '0');
        const minutes = String(remainingTime.getUTCMinutes()).padStart(2, '0');
        const seconds = String(remainingTime.getUTCSeconds()).padStart(2, '0');
        displayElement.textContent = `${hours}:${minutes}:${seconds}`;
        displayElement.classList.remove('text-danger', 'blink');
    }, 1000);
    // зберігаємо інтервал у глобальній змінній
    window.workTimerInterval = workTimerInterval;
}

function pauseTimerWork() {
    const displayElement = document.getElementById('worktimerDisplay');
    const elapsedTime = displayElement.textContent.split(':').map(Number);
    const elapsedMilliseconds = (elapsedTime[0] * 3600 + elapsedTime[1] * 60 + elapsedTime[2]) * 1000;

    // Зберегти час у мілісекундах
    displayElement.dataset.starttime = (elapsedMilliseconds / 1000 / 60 / 60).toFixed(2); // зберігаємо в годинах

    // Очистити інтервал
    clearInterval(window.workTimerInterval);
}


function countWorkers() {
    const checked = document.querySelectorAll('input[name="worker[]"]:checked');
    return checked.length;
}
{{ ($lastAction && in_array($lastAction->action_type, ['start', 'resume'])) ? 'startTimer(); startTimerWork();' : '' }}
 
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('endWorkForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // блокуємо стандартну поведінку

        const url = "{{ route('tasks.complete', $task->id) }}";
        const formData = new FormData(form);
        formData.append('task_id', '{{ $task->id }}');

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
                const modal = bootstrap.Modal.getInstance(document.getElementById('endWorkModal'));
                modal.hide();
                // остановити таймери, сховати кнопки перерва та завершення показати кнопку почати роботу
                pauseTimer();
                pauseTimerWork();
                document.getElementById('pauseBtn').classList.add('d-none');
                document.getElementById('endBtn').classList.add('d-none');
                document.getElementById('startBtn').classList.remove('d-none');
               // location.reload();
            } else {
                console.error('Помилка відповіді:', data);
            }
        })
        .catch(error => console.error('Fetch помилка:', error));
    });
});

</script>
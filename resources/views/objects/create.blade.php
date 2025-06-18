@extends('app.layout')

@section('title',  'Картка завдання')


@section('content')
<div class="container mt-4">
  <h3 class="mb-3">Картка завдання</h3>

  <!-- Кнопки -->
  <div class="mb-3">
    <button type="button" class="btn btn-success me-2" id="startBtn">▶ Почати роботу</button>
    <button type="button" class="btn btn-warning me-2 d-none" id="pauseBtn">⏸ Перерва</button>
    <button type="button" class="btn btn-danger me-2 d-none" id="stopBtn">⏹ Завершити роботу</button>
    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 Проблема</button>
    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓</button>
  </div>

  <!-- Таймер -->
  <div class="mb-3 d-none" id="timerSection">
    <label class="form-label fw-bold">Початок роботи: <i id="startTime"></i></label><br>
    <label class="form-label fw-bold">Перерва: <i id="pauseTime"></i></label><br>
    <label class="form-label fw-bold">Час роботи: <i id="timer">1:24</i></label><br>
  </div>


<!-- Модальне вікно завершення роботи -->
<div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="finishModalLabel">Завершення роботи</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        <label for="workDone" class="form-label">Скільки зроблено:</label>
        <input type="text" class="form-control" id="workDone" required>
        <label for="workPhoto" class="form-label mt-3">Завантажити фото:</label>
        <input type="file" class="form-control" id="workPhoto">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Зберегти</button>
      </div>
    </form>
  </div>
</div>

<script>
let timerInterval;
let startTime, pauseTime, elapsedTime = 0;

document.getElementById('startBtn').addEventListener('click', function() {
    startTime = new Date();
    document.getElementById('startTime').innerText = startTime.toLocaleTimeString();
    document.getElementById('timerSection').classList.remove('d-none');
    document.getElementById('pauseBtn').classList.remove('d-none');
    document.getElementById('stopBtn').classList.remove('d-none');
    this.classList.add('d-none');

    timerInterval = setInterval(() => {
        elapsedTime++;
        document.getElementById('timer').innerText = new Date(elapsedTime * 1000).toISOString().substr(11, 8);
    }, 1000);
});

document.getElementById('pauseBtn').addEventListener('click', function() {
    clearInterval(timerInterval);
    pauseTime = new Date();
    document.getElementById('pauseTime').innerText = pauseTime.toLocaleTimeString();
});

document.getElementById('stopBtn').addEventListener('click', function() {
    clearInterval(timerInterval);
    new bootstrap.Modal(document.getElementById('finishModal')).show();
});
</script>

 

  <!-- Персонал -->
  <div class="mb-3">
    <label class="form-label fw-bold">Персонал на об'єкті:</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker1">
      <label class="form-check-label" for="worker1">Іванов І.І.</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker2">
      <label class="form-check-label" for="worker2">Петренко П.П.</label>
    </div>
    <!-- Додайте ще працівників -->
  </div>

  <!-- ТМЦ -->
  <div class="mb-3">
    <label class="form-label fw-bold">Наявність ТМЦ:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc1">
      <label class="form-check-label" for="tmc1">Цемент</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc2">
      <label class="form-check-label" for="tmc2">Арматура</label>
    </div>
  </div>

  <!-- Обладнання -->
  <div class="mb-3">
    <label class="form-label fw-bold">Наявність обладнання:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip1">
      <label class="form-check-label" for="equip1">Віброплита</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip2">
      <label class="form-check-label" for="equip2">Лазерний рівень</label>
    </div>
  </div>

  <!-- Робота техніки -->
  <div class="mb-3">
    <label class="form-label fw-bold">Робота техніки:</label>
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-success"  id="machinery1" placeholder="Наприклад: екскаватор">Екскаватор працює</button >
      </div>
      <div class="col-md-2">
        <label for="techStart" class="form-label">Початок</label>
        <input type="time" class="form-control" id="techStart" value ="08:00">
      </div>
      <div class="col-md-2">
        <label for="techEnd" class="form-label">Кінець</label>
        <input type="time" class="form-control" id="techEnd">
      </div>
    </div>
  </div>
  <div class="mb-3">
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-warning" id="machinery2" placeholder="Наприклад: самосвал">Самосвал не працює</button>
      </div>
      <div class="col-md-2">
        <label for="techStart2" class="form-label">Початок</label>
        <input type="time" class="form-control" id="techStart2"> 
      </div>
      <div class="col-md-2">
        <label for="techEnd" class="form-label">Кінець</label>
        <input type="time" class="form-control" id="techEnd">
      </div>
    </div>
  </div>   
</div>

<!-- Модальне вікно: проблема -->
<div class="modal fade" id="problemModal" tabindex="-1" aria-labelledby="problemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="problemModalLabel">Повідомити про проблему</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        <label for="problemText" class="form-label">Опишіть проблему:</label>
        <textarea class="form-control" id="problemText" rows="4" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Надіслати</button>
      </div>
    </form>
  </div>
</div>

<!-- Модальне вікно: допомога -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Довідка по формі</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li><strong>Почати роботу:</strong> натисніть на старт перед фактичним початком.</li>
          <li><strong>Перерва:</strong> фіксує перерву в роботі.</li>
          <li><strong>Завершити роботу:</strong> фіксує час завершення.</li>
          <li><strong>Персонал:</strong> позначте працівників, які реально працюють на об'єкті.</li>
          <li><strong>ТМЦ:</strong> оберіть, якщо матеріали присутні на місці.</li>
          <li><strong>Обладнання:</strong> відзначте наявне обладнання.</li>
          <li><strong>Робота техніки:</strong> кликнить на кнопці,  час роботи техніки заповниться автоматично.</li>
          <li><strong>Проблема:</strong> натисніть "🚨 Проблема", якщо виникає будь-яке ускладнення.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<script>
 
</script>
@endsection


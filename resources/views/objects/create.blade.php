@extends('app.layout')

@section('title',  'Картка завдання')


@section('content')
<div class="container mt-4">
  <h3 class="mb-3">{{ __('Task Card') }}  </h3>

  <!-- Кнопки -->
  <div class="mb-3">
    <button type="button" class="btn btn-success me-2" id="startBtn">▶ {{ __('Start Work') }}</button>
    <button type="button" class="btn btn-warning me-2 d-none" id="pauseBtn">⏸ {{ __('Pause') }}</button>
    <button type="button" class="btn btn-danger me-2 d-none" id="stopBtn">⏹ {{ __('Stop Work') }}</button>
    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 {{ __('Problem') }}</button>
    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓</button>
  </div>

  <!-- Таймер -->
  <div class="mb-3 d-none" id="timerSection">
    <label class="form-label fw-bold">{{ __('Work Start') }}: <i id="startTime"></i></label><br>
    <label class="form-label fw-bold">{{ __('Break') }}: <i id="pauseTime"></i></label><br>
    <label class="form-label fw-bold">{{ __('Work Time') }}: <i id="timer">1:24</i></label><br>
  </div>


<!-- Модальне вікно завершення роботи -->
<div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="finishModalLabel">{{ __('Finish Work') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
      </div>
      <div class="modal-body">
        <label for="workDone" class="form-label">{{ __('Work Done') }}:</label>
        <input type="text" class="form-control" id="workDone" required>
        <label for="workPhoto" class="form-label mt-3">{{ __('Upload Photo') }}:</label>
        <input type="file" class="form-control" id="workPhoto">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
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
    <label class="form-label fw-bold">{{ __('Personnel on Site') }}:</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker1">
      <label class="form-check-label" for="worker1">{{ __('Ivanov I.I.') }}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker2">
      <label class="form-check-label" for="worker2">{{ __('Petrov P.P.') }}</label>
    </div>
    <!-- Додайте ще працівників -->
  </div>

  <!-- ТМЦ -->
  <div class="mb-3">
    <label class="form-label fw-bold">{{ __('Availability of Materials') }}:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc1">
      <label class="form-check-label" for="tmc1">{{ __('Cement') }}</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc2">
      <label class="form-check-label" for="tmc2">{{ __('Rebar') }}</label>
    </div>
  </div>

  <!-- Обладнання -->
  <div class="mb-3">
    <label class="form-label fw-bold">{{ __('Availability of Equipment') }}:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip1">
      <label class="form-check-label" for="equip1">{{ __('Vibrating Plate') }}</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip2">
      <label class="form-check-label" for="equip2">{{ __('Laser Level') }}</label>
    </div>
  </div>

  <!-- Робота техніки -->
  <div class="mb-3">
    <label class="form-label fw-bold">{{ __('Machinery Operation') }}:</label>
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-success"  id="machinery1" placeholder="Наприклад: екскаватор">{{ __('Excavator is Working') }}</button >
      </div>
      <div class="col-md-2">
        <label for="techStart" class="form-label">{{ __('Start Time') }}</label>
        <input type="time" class="form-control" id="techStart" value ="08:00">
      </div>
      <div class="col-md-2">
        <label for="techEnd" class="form-label">{{ __('End Time') }}  </label>
        <input type="time" class="form-control" id="techEnd">
      </div>
    </div>
  </div>
  <div class="mb-3">
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-warning" id="machinery2" placeholder="Наприклад: самосвал">{{ __('Dump Truck is Not Working') }}</button>
      </div>
      <div class="col-md-2">
        <label for="techStart2" class="form-label">{{ __('Start Time') }}</label>
        <input type="time" class="form-control" id="techStart2"> 
      </div>
      <div class="col-md-2">
        <label for="techEnd2" class="form-label">{{ __('End Time') }}</label>
        <input type="time" class="form-control" id="techEnd2">
      </div>
    </div>
  </div>   
</div>

<!-- Модальне вікно: проблема -->
<div class="modal fade" id="problemModal" tabindex="-1" aria-labelledby="problemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="problemModalLabel">{{ __('Report Problem') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        <label for="problemText" class="form-label">{{ __('Describe the problem') }}:</label>
        <textarea class="form-control" id="problemText" rows="4" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">{{ __('Submit') }}</button>
      </div>
    </form>
  </div>
</div>

<!-- Модальне вікно: допомога -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">{{ __('Form Help') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li><strong>{{ __('Start Work') }}:</strong> click the start button before the actual start.</li>
          <li><strong>{{ __('Break') }}:</strong> records a break in work.</li>
          <li><strong>{{ __('End Work') }}:</strong> records the time of completion.</li>
          <li><strong>{{ __('Personnel') }}:</strong> mark employees who are actually working on the site.</li>
          <li><strong>{{ __('Materials') }}:</strong> select if materials are present on the site.</li>
          <li><strong>{{ __('Equipment') }}:</strong> mark available equipment.</li>
          <li><strong>{{ __('Equipment Work') }}:</strong> click the button, and the equipment work time will be filled in automatically.</li>
          <li><strong>{{ __('Problem') }}:</strong> click "🚨 Problem" if any complications arise.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<script>
 
</script>
@endsection


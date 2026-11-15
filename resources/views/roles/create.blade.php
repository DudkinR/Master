@extends('app.layout')

@section('title',  'Картка завдання')
@section('content')
<div class="container mt-4">
  <h3 class="mb-3"> {{ __('Task Card') }} </h3>

  <!-- Кнопки -->
  <div class="mb-3">
    <button type="button" class="btn btn-success me-2">▶ {{ __('Start Work') }}</button>
    <button type="button" class="btn btn-warning me-2">⏸ {{ __('Break') }}</button>
    <button type="button" class="btn btn-danger me-2">⏹ {{ __('End Work') }}</button>
    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 {{ __('Problem') }}</button>
    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓ {{ __('Help') }} </button>
  </div>

  <!-- Персонал -->
  <div class="mb-3">
    <label class="form-label fw-bold"> {{ __('Personnel on Site') }}:</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker1">
      <label class="form-check-label" for="worker1"> {{ __('Ivanov I.I.') }}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker2">
      <label class="form-check-label" for="worker2"> {{ __('Petrenko P.P.') }}</label>
    </div>
    <!-- Додайте ще працівників -->
  </div>

  <!-- ТМЦ -->
  <div class="mb-3">
    <label class="form-label fw-bold"> {{ __('Availability of Materials') }}:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc1">
      <label class="form-check-label" for="tmc1"> {{ __('Cement') }}</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tmc2">
      <label class="form-check-label" for="tmc2"> {{ __('Rebar') }}</label>
    </div>
  </div>

  <!-- Обладнання -->
  <div class="mb-3">
    <label class="form-label fw-bold"> {{ __('Availability of Equipment') }}:</label><br>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip1">
      <label class="form-check-label" for="equip1"> {{ __('Vibrating Plate') }}</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="equip2">
      <label class="form-check-label" for="equip2"> {{ __('Laser Level') }}</label>
    </div>
  </div>

  <!-- Робота техніки -->
  <div class="mb-3">
    <label class="form-label fw-bold"> {{ __('Operation of Equipment') }}:</label>
    <div class="row">
      <div class="col-md-6">
        <label for="techStart" class="form-label"> {{ __('Start') }}</label>
        <input type="time" class="form-control" id="techStart">
      </div>
      <div class="col-md-6">
        <label for="techEnd" class="form-label"> {{ __('End') }}</label>
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
        <h5 class="modal-title" id="problemModalLabel"> {{ __('Report a Problem') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="problemText" class="form-label"> {{ __('Describe the problem') }}:</label>
        <textarea class="form-control" id="problemText" rows="4" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger"> {{ __('Send') }}</button>
      </div>
    </form>
  </div>
</div>

<!-- Модальне вікно: допомога -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel"> {{ __('Help') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <li><strong> {{ __('Start Work') }}:</strong> click the start button before actually starting.</li>
          <li><strong> {{ __('Break') }}:</strong> records a break in work.</li>
          <li><strong> {{ __('Finish Work') }}:</strong> records the time of completion.</li>
          <li><strong> {{ __('Staff') }}:</strong> mark employees who are actually working on the site.</li>
          <li><strong> {{ __('Materials') }}:</strong> select if materials are present on the site.</li>
          <li><strong> {{ __('Equipment') }}:</strong> mark the available equipment.</li>
          <li><strong> {{ __('Operation of Equipment') }}:</strong> fill in the time of equipment operation.</li>
          <li><strong> {{ __('Problem') }}:</strong> click "🚨 Problem" if any complications arise.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection

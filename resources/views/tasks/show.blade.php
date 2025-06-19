@extends('layouts.app')
@section('title', 'Проекти')
@section('content')
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif



<div class="container mt-4">
  <h3 class="mb-3">Картка завдання</h3>

  <!-- Кнопки -->
  <div class="mb-3">
    <button type="button" class="btn btn-success me-2">▶ Почати роботу</button>
    <button type="button" class="btn btn-warning me-2" style="hidden">⏸ Перерва</button>
    <button type="button" class="btn btn-danger me-2" style="hidden">⏹ Завершити роботу</button>
    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#problemModal">🚨 Проблема</button>
    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">❓</button>
  </div>
  


  <!-- Персонал -->
  <div class="mb-3">
    <label class="form-label fw-bold">Персонал на об'єкті:</label><br>
    @foreach ($task->acceptMaster as $brigada)
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="worker_{{$brigada->id}}" checked>
      <label class="form-check-label" for="worker_{{$brigada->id}}">{{ $brigada->name }}</label>
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

  <!-- Робота техніки -->
  <div class="mb-3">
    <label class="form-label fw-bold">Робота техніки:</label>
    @foreach ($task->machines as $machine)
    <div class="form-check form-check-inline">
        <button  type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#techModal_{{ $machine->id }}">
            
            Початок роботи {{ $machine->name }}
        </button>
    </div>
    <div class="row">
      <div class="col-md-6">
        <label for="techStart" class="form-label">Початок</label>
        <input type="time" class="form-control" id="techStart">
      </div>
      <div class="col-md-6">
        <label for="techEnd" class="form-label">Кінець</label>
        <input type="time" class="form-control" id="techEnd">
      </div>
    </div>
    @endforeach
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
          <li><strong>Робота техніки:</strong> заповніть час роботи техніки.</li>
          <li><strong>Проблема:</strong> натисніть "🚨 Проблема", якщо виникає будь-яке ускладнення.</li>
        </ul>
      </div>
    </div>
  </div>
</div>



    


@endsection
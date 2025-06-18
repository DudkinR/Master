@extends('app.layout')

@section('title', 'Додавання робіт до проєкту')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">Форма додавання робіт до проєкту</h2>
    <form>

      <!-- Порядковий номер -->
      <div class="mb-3">
        <label for="taskOrder" class="form-label">Порядок виконання</label>
        <input type="number" class="form-control" id="taskOrder" name="taskOrder" min="1" required>
      </div>

      <!-- Найменування роботи -->
      <div class="mb-3">
        <label for="taskName" class="form-label">Найменування роботи</label>
        <input type="text" class="form-control" id="taskName" name="taskName" required>
      </div>

      <!-- Людино-години -->
      <div class="mb-3">
        <label for="manHours" class="form-label">Кількість людино-годин</label>
        <input type="number" class="form-control" id="manHours" name="manHours" min="0" required>
      </div>

      <!-- Потрібне ТМЦ -->
      <div class="mb-3">
        <label for="tmc" class="form-label">Потрібне ТМЦ</label>
        <textarea class="form-control" id="tmc" name="tmc" rows="2" placeholder="Наприклад: цемент – 10 мішків, арматура – 30 м"></textarea>
      </div>

      <!-- Потрібне обладнання -->
      <div class="mb-3">
        <label for="equipment" class="form-label">Потрібне обладнання</label>
        <textarea class="form-control" id="equipment" name="equipment" rows="2" placeholder="Наприклад: віброплита, лазерний рівень"></textarea>
      </div>

      <!-- Потрібна техніка та час використання -->
      <div class="mb-3">
        <label for="machinery" class="form-label">Потрібна техніка</label>
        <input type="text" class="form-control mb-2" id="machinery" name="machinery" placeholder="Наприклад: екскаватор">
        
        <label for="machineHours" class="form-label">Кількість годин роботи техніки</label>
        <input type="number" class="form-control" id="machineHours" name="machineHours" min="0" placeholder="Наприклад: 4">
      </div>

      <!-- Кнопка додавання -->
      <button type="submit" class="btn btn-success">Додати роботу</button>

    </form>
  </div>
@endsection
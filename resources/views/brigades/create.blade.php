@extends('app.layout')

@section('title',  'Призначення бригади на завдання')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">Призначення бригади на завдання</h2>
    
    <form>

      <!-- Назва завдання -->
      <div class="mb-3">
        <label for="taskName" class="form-label">Найменування завдання</label>
        <input type="text" class="form-control" id="taskName" name="taskName" required>
      </div>

      <!-- Вибір бригадира -->
      <div class="mb-3">
        <label for="foreman" class="form-label">Бригадир</label>
        <select class="form-select" id="foreman" name="foreman" required>
          <option selected disabled>Оберіть бригадира</option>
          <option value="ivanov">Іванов І.І.</option>
          <option value="shevchenko">Шевченко О.О.</option>
          <option value="melnyk">Мельник В.В.</option>
        </select>
      </div>

      <!-- Список працівників -->
      <div class="mb-3">
        <label for="workers" class="form-label">Склад бригади</label>
        <select multiple class="form-select" id="workers" name="workers[]" required>
          <option value="pratsivnyk1">Петренко А.А.</option>
          <option value="pratsivnyk2">Сидоренко Б.Б.</option>
          <option value="pratsivnyk3">Коваль В.В.</option>
          <option value="pratsivnyk4">Литвиненко Г.Г.</option>
          <!-- Додати ще працівників за потреби -->
        </select>
        <div class="form-text">Утримуйте Ctrl (або Cmd), щоб обрати кількох працівників.</div>
      </div>

      <!-- Дата і час початку -->
      <div class="mb-3">
        <label for="startDateTime" class="form-label">Дата та час початку</label>
        <input type="datetime-local" class="form-control" id="startDateTime" name="startDateTime" required>
      </div>

      <!-- Дата і час завершення -->
      <div class="mb-3">
        <label for="endDateTime" class="form-label">Дата та час завершення</label>
        <input type="datetime-local" class="form-control" id="endDateTime" name="endDateTime" required>
      </div>

      <!-- Кнопка надсилання -->
      <button type="submit" class="btn btn-primary">Призначити бригаду</button>

    </form>
  </div>

@endsection

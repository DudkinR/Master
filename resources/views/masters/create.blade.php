@extends('app.layout')

@section('title', 'Призначення відповідального майстра')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">Призначення відповідального майстра до завдання</h2>
    
    <form>

      <!-- Назва завдання -->
      <div class="mb-3">
        <label for="taskName" class="form-label">Найменування завдання</label>
        <input type="text" class="form-control" id="taskName" name="taskName" required>
      </div>

      <!-- Вибір майстра -->
      <div class="mb-3">
        <label for="responsibleMaster" class="form-label">Відповідальний майстер</label>
        <select class="form-select" id="responsibleMaster" name="responsibleMaster" required>
          <option selected disabled>Оберіть майстра</option>
          <option value="майстер_1">Іваненко І.О.</option>
          <option value="майстер_2">Петренко П.П.</option>
          <option value="майстер_3">Сидоренко С.С.</option>
          <!-- Додайте інших майстрів за потреби -->
        </select>
      </div>

      <!-- Дата початку -->
      <div class="mb-3">
        <label for="startDate" class="form-label">Дата початку виконання</label>
        <input type="date" class="form-control" id="startDate" name="startDate" required>
      </div>

      <!-- Дата завершення -->
      <div class="mb-3">
        <label for="endDate" class="form-label">Дата завершення виконання</label>
        <input type="date" class="form-control" id="endDate" name="endDate" required>
      </div>

      <!-- Кнопка збереження -->
      <button type="submit" class="btn btn-primary">Призначити завдання</button>

    </form>
  </div>
@endsection

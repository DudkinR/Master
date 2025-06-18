@extends('app.layout')

@section('title',  'Реєстрація будівельного проєкту')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">Форма реєстрації будівельного проєкту</h2>
    <form>

      <!-- Назва проєкту -->
      <div class="mb-3">
        <label for="projectName" class="form-label">Назва проєкту</label>
        <input type="text" class="form-control" id="projectName" name="projectName" required>
      </div>

      <!-- Локація -->
      <div class="mb-3">
        <label for="location" class="form-label">Місце реалізації (локація)</label>
        <input type="text" class="form-control" id="location" name="location" required>
      </div>

      <!-- Керівник проєкту -->
      <div class="mb-3">
        <label for="manager" class="form-label">Керівник проєкту</label>
        <input type="text" class="form-control" id="manager" name="manager">
      </div>

      <!-- Орієнтовна дата початку -->
      <div class="mb-3">
        <label for="startDate" class="form-label">Дата початку робіт</label>
        <input type="date" class="form-control" id="startDate" name="startDate">
      </div>

      <!-- Орієнтовна дата завершення -->
      <div class="mb-3">
        <label for="endDate" class="form-label">Дата завершення робіт</label>
        <input type="date" class="form-control" id="endDate" name="endDate">
      </div>

      <!-- Загальний кошторис -->
      <div class="mb-3">
        <label for="budget" class="form-label">Загальний кошторис (грн)</label>
        <input type="number" class="form-control" id="budget" name="budget" min="0" step="0.01">
      </div>

      <!-- Короткий опис проєкту -->
      <div class="mb-3">
        <label for="description" class="form-label">Короткий опис проєкту</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>

      <!-- Кнопка надсилання -->
      <button type="submit" class="btn btn-primary">Зареєструвати</button>

    </form>
  </div>

@endsection
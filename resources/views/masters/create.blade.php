@extends('app.layout')

@section('title', 'Призначення відповідального майстра')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">
      {{ __('Assign Responsible Master to Task') }}
    </h2>

    <form>

      <!-- Назва завдання -->
      <div class="mb-3">
        <label for="taskName" class="form-label">{{ __('Task Name') }}</label>
        <input type="text" class="form-control" id="taskName" name="taskName" required>
      </div>

      <!-- Вибір майстра -->
      <div class="mb-3">
        <label for="responsibleMaster" class="form-label">{{ __('Responsible Master') }}</label>
        <select class="form-select" id="responsibleMaster" name="responsibleMaster" required>
          <option selected disabled>{{ __('Select Master') }}</option>
          <option value="майстер_1">{{ __('Ivanenko I.O.') }}</option>
          <option value="майстер_2">{{ __('Petrov P.P.') }}</option>
          <option value="майстер_3">{{ __('Sidorenko S.S.') }}</option>
          <!-- Додайте інших майстрів за потреби -->
        </select>
      </div>

      <!-- Дата початку -->
      <div class="mb-3">
        <label for="startDate" class="form-label">{{ __('Start Date') }}</label>
        <input type="date" class="form-control" id="startDate" name="startDate" required>
      </div>

      <!-- Дата завершення -->
      <div class="mb-3">
        <label for="endDate" class="form-label">{{ __('End Date') }}</label>
        <input type="date" class="form-control" id="endDate" name="endDate" required>
      </div>

      <!-- Кнопка збереження -->
      <button type="submit" class="btn btn-primary">{{ __('Assign Task') }}</button>

    </form>
  </div>
@endsection

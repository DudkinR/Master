@extends('app.layout')

@section('title',  'Призначення бригади на завдання')
@section('content')
  <div class="container mt-5">
    <h2 class="mb-4">Призначення бригади на завдання</h2>
    
    <form>

      <!-- Назва завдання -->
      <div class="mb-3">
        <label for="taskName" class="form-label">
          {{ __('Task Name') }}
          </label>
        <input type="text" class="form-control" id="taskName" name="taskName" required>
      </div>

      <!-- Вибір бригадира -->
      <div class="mb-3">
        <label for="foreman" class="form-label">
          {{ __('Foreman') }}
</label>
        <select class="form-select" id="foreman" name="foreman" required>
          <option selected disabled>
            {{ __('Select Foreman') }}
          </option>
          <option value="ivanov">
            {{ __('Ivanov I.I.') }}
          </option>
          <option value="shevchenko">
            {{ __('Shevchenko O.O.') }}
          </option>
          <option value="melnyk">
            {{ __('Melnyk V.V.') }}
          </option>
        </select>
      </div>

      <!-- Список працівників -->
      <div class="mb-3">
        <label for="workers" class="form-label">
          {{ __('Workers') }}
        </label>
        <select multiple class="form-select" id="workers" name="workers[]" required>
          <option value="pratsivnyk1">
            {{ __('Petrenko A.A.') }}
          </option>
          <option value="pratsivnyk2">
            {{ __('Sidorenko B.B.') }}
          </option>
          <option value="pratsivnyk3">
            {{ __('Koval V.V.') }}
          </option>
          <option value="pratsivnyk4">
            {{ __('Litvinenko G.G.') }}
          </option>
          <!-- Додати ще працівників за потреби -->
        </select>
        <div class="form-text">Утримуйте Ctrl (або Cmd), щоб обрати кількох працівників.</div>
      </div>

      <!-- Дата і час початку -->
      <div class="mb-3">
        <label for="startDateTime" class="form-label">
          {{ __('Start Date and Time') }}
        </label>
        <input type="datetime-local" class="form-control" id="startDateTime" name="startDateTime" required>
      </div>

      <!-- Дата і час завершення -->
      <div class="mb-3">
        <label for="endDateTime" class="form-label">
          {{ __('End Date and Time') }}
        </label>
        <input type="datetime-local" class="form-control" id="endDateTime" name="endDateTime" required>
      </div>

      <!-- Кнопка надсилання -->
      <button type="submit" class="btn btn-primary">Призначити бригаду</button>

    </form>
  </div>

@endsection

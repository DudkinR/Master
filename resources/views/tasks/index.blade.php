@extends('layouts.app')
@section('title', 'Завдання')
@section('content')
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif
    <!-- всі завдання -->
    
        @foreach ($tasks as $task)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card mb-8 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->name }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <p><strong>Порядок:</strong> {{ $task->order }}</p>
                                <p><strong>Дата початку:</strong> {{ $task->start_date }}</p>
                                <p><strong>Дата завершення:</strong> {{ $task->end_date }}</p>
                                <p><strong>Орієнтовні години:</strong> {{ $task->estimated_hours }} год</p>
                    </div>
                     <!-- призначин мастер -->
                    <div class="card-footer">
                        <p><strong>Призначений майстер:</strong> 
                           @if ($task->master->first())
                                {{ $task->master->first()->name }}
                                <a href="{{ route('tasks.Brigadirshow', $task->id) }}" class="btn btn-secondary">Мастер завдання</a>
                            @else
                                <span class="bg-danger text-white">Не призначено</span>
                      
                            @endif
                            <button class="btn btn-secondary float-end" data-bs-toggle="modal" data-bs-target="#assignMasterModal" 
                                    data-task-id="{{ $task->id }}" data-action="{{ route('tasks.assignMaster', $task->id) }}">
                                Призначити майстра
                            </button>
                        </p>
                        <p><strong>Статус:</strong> 
                            @if ($task->status == 'completed')
                                <span class="badge bg-success">Виконано</span>
                            @elseif ($task->status == 'in_progress')
                                <span class="badge bg-warning text-dark">В процесі</span>
                            @else
                                <span class="badge bg-secondary">Не розпочато</span>
                            @endif
                        </p>
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary">Переглянути деталі</a>
                        
          
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
 <!-- Modal для призначення майстра -->
<div class="modal fade" id="assignMasterModal" tabindex="-1" aria-labelledby="assignMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignMasterModalLabel">Призначити майстра</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignMasterForm" method="POST" action="{{ route('tasks.assignMaster') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="mb-3">
                        <label for="master_id" class="form-label">Оберіть майстра</label>
                        <select class="form-select" name="master_id" id="master_id" required>
                            <option value="">-- Виберіть майстра --</option>
                            @foreach ($masters as $master)
                                <option value="{{ $master->id }}">{{ $master->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                    <button type="submit" class="btn btn-primary">Призначити</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const assignMasterModal = document.getElementById('assignMasterModal');

    assignMasterModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Кнопка, яка відкрила модальне вікно
        const taskId = button.getAttribute('data-task-id');
        const actionUrl = button.getAttribute('data-action');

        // Підставляємо значення у форму
        const modal = assignMasterModal;
        modal.querySelector('#task_id').value = taskId;

        // Якщо action динамічний — оновлюємо
        if (actionUrl) {
            modal.querySelector('form').setAttribute('action', actionUrl);
        }
    });
});
</script>
@endsection

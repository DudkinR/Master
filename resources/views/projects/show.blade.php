@extends('layouts.app')
@section('title', 'Проект - ' . $project->name)
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6 mb-4">
             <h1>{{ $project->name }}</h1>
            <p>{{ $project->description }}</p>
            <p><strong>Створено:</strong> {{ $project->created_at->format('d.m.Y H:i') }}</p>
            <p><strong>Оновлено:</strong> {{ $project->updated_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="col-md-6 mb-4">
            @if($project->location !== null) 
            @php 
                $location = explode(',', $project->location);
                $latitude = trim($location[0]);
                $longitude = trim($location[1]);
            @endphp
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2157.4766747305384!2d{{ $longitude }}!3d{{ $latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1750329277888" 
                width="400" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        @endif
    </div>
     @if(Auth::user()->roles->first()->name ==="Boss" || Auth::user()->roles->first()->name ==="Engineer" || Auth::user()->roles->first()->name ==="Admin")
    <div class="mt-4">
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Редагувати</a>
        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Видалити</button>
        </form>
    </div>
    @endif
    <div class="mt-5">
        <h3>Завдання в рамках цього проекту</h3>
                    <!-- Кнопка відкриття модального вікна -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                Створити нове завдання
            </button>

            <!-- Модальне вікно -->
            <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> {{-- Збільшений розмір --}}
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                     <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createTaskModalLabel">Створити нове завдання</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                        </div>
                        <div class="modal-body">
                                <div class="row g-3">
                        <div class="col-md-6">
                            <label for="order" class="form-label">Порядок</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ $project->tasks->count() + 1 }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Назва</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Опис</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Початок</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Завершення</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ now()->addDays(30)->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="estimated_hours" class="form-label">Оцінка годин</label>
                            <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" step="0.01" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="actual_hours" class="form-label">Факт годин</label>
                            <input type="number" class="form-control" id="actual_hours" name="actual_hours" step="0.01" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="progress" class="form-label">Прогрес %</label>
                            <input type="number" class="form-control" id="progress" name="progress" max="100" value="0">
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Кількість</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" step="1" value="0">
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Одиниця</label>
                            <select class="form-select" id="unit" name="unit">
                                <option disabled selected>Оберіть...</option>
                                <option value="шт">Штуки</option>
                                <option value="м">Метри</option>
                                <option value="м2">м²</option>
                                <option value="м3">м³</option>
                                <option value="год">Години</option>
                                <option value="т">Тони</option>
                                <option value="л">Літри</option>
                                <option value="кг">Кілограми</option>
                                <option value="шт/год">Шт/год</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="etalon_task" class="form-label">Еталонне</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="etalon_task" name="etalon_task">
                                <label class="form-check-label" for="etalon_task">Так</label>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <label for="submit_button" class="form-label">Кнопка відправки</label>
                            <button type="submit" class="btn btn-primary w-100" id="submit_button">Створити завдання</button>
                        </div>
                    </div>

                </div>
                        
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
       
            @if($project->tasks->isEmpty())
                <p>Немає завдань для цього проекту.</p>
            @else
            <div class="container">
                @foreach($project->tasks as $task)
                <div class="row">
                    <div class="col-md-4 mb-4">
                    
                    <h6>{{ $task->name }} <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary mb-2">🔍</a>
                   </h6>
                        <p>{{ $task->description }}</p>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6>Матеріали</h6>                        
                        {{$task->materials->count() > 0 ? $task->materials->count() : 'Немає матеріалів'}}
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6>Обладнання</h6>
                        {{$task->equipments->count() > 0 ? $task->equipments->count() : 'Немає обладнання'}}
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6>Техніка</h6>
                        {{$task->machines->count() > 0 ? $task->machines->count() : 'Немає техніки'}}
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6>Намечена дата</h6>
                        <p>{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') : 'Не вказано' }}</p>
                        <h6>Дата завершення</h6>
                        <p>{{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') : 'Не вказано' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
    </div>

</div>
@endsection

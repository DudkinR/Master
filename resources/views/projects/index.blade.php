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
    <!-- новий проект -->
    <div class="row mb-4">
        <div class="col-md-12">
            <!-- кнопка для відкриття модального вікна -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                Створити новий проект
            </button>
            
            <!-- модальне вікно -->
            <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createProjectModalLabel">Створити новий проект</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('projects.store') }}" method="POST">
                                @csrf
                              <div class="container mt-5">
                                <h2 class="mb-4">Форма реєстрації будівельного проєкту</h2>                 
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- список проектів -->
    <div class="row">
        <div class="col-md-12">
            <h2>Список проектів</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Опис</th>
                        <th>Дії</th>
                        <th>Локація</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->description }}</td>
                        <td>
                            <div class="btn-group btn-group-sm d-flex flex-column gap-1" role="group">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info w-100">👁 Переглянути</a>
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning w-100">✏️ Редагувати</a>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Точно видалити?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">🗑 Видалити</button>
                                </form>
                            </div>
                        </td>
                        <td>
                            @php 
                                [$latitude, $longitude] = array_map('trim', explode(',', $project->location));
                            @endphp
                            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#map-{{ $project->id }}">
                                🗺 Показати карту
                            </button>
                            <div id="map-{{ $project->id }}" class="collapse mt-2">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2157.4766747305384!2d{{ $longitude }}!3d{{ $latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1750329277888"
                                    width="100%" height="250" style="border:0;" loading="lazy" allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
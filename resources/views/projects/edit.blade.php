
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

    <!-- edit проект not modal -->
    <div class="row mb-4">
        <div class="col-md-12">
            <!-- кнопка для відкриття модального вікна -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                Редагувати проект: {{ $project->name }}
            </button>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="container mt-5">
                    <h2 class="mb-4">Форма редагування будівельного проєкту</h2>
                    <!-- Назва проєкту -->
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Назва проєкту</label>
                        <input type="text" class="form-control" id="projectName" name="projectName" value="{{ $project->name }}" required>
                    </div>
                </div>
                <!-- Локація -->
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="location" class="form-label">Локація</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ $project->location }}" required>
                    </div>@php 
                        $isCoordinates = preg_match('/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/', $project->location);
                        @endphp
                  <div class="col-md-6">
                    @php
                        $location = $isCoordinates
                            ? $project->location
                            : ($enterprise_location ?? null);

                        [$latitude, $longitude] = [null, null];

                        if ($location && preg_match('/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/', $location)) {
                            [$latitude, $longitude] = array_map('trim', explode(',', $location));
                        }
                    @endphp

                    @if($latitude && $longitude)
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#map-{{ $project->id }}">
                            🗺 Показати карту
                        </button>
                        <div id="map-{{ $project->id }}" class="collapse mt-2">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2157.4766747305384!2d{{ $longitude }}!3d{{ $latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1750329277888"
                                width="100%" height="250" style="border:0;" loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        
                    @endif
                    <div id="help-{{ $project->id }}" class="mt-2">
                            <p>Допомога по локації:</p>
                            <ul>
                                <li>Якщо ви не знаєте точні координати, спробуйте знайти їх на Google Maps.</li>
                                <li>Перейдить "Переглянути збільшену карту" та знайдіть потрібну локацію.</li>
                                <li>натиснить правою кнопкою миші на точку на карті та виберіть "Що тут?"</li>
                                <li>Кликнить на першу строку в вигляді "Широта, Довгота"  приклад: 48.8588443, 2.2943506</li>
                                <li>Вставте координати в поле "Локація" у форматі "Широта, Довгота".</li>
                            </ul>
                        </div>
                </div>

                </div>
                <!-- Керівник проєкту -->
                <div class="mb-3">
                    <label for="manager" class="form-label">Керівник проєкту</label>
                    <input type="text" class="form-control" id="manager" name="manager" value="{{ $project->manager }}">
                </div>
                <!-- Орієнтовна дата початку -->
                <div class="mb-3">
                    <label for="startDate" class="form-label">Дата початку робіт</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="{{ $project->start_date }}">
                </div>
                <!-- Орієнтовна дата завершення -->
                <div class="mb-3">
                    <label for="endDate" class="form-label">Дата завершення робіт</label>
                    <input type="date" class="form-control" id="endDate" name="endDate" value="{{ $project->end_date }}">
                </div>
                <!-- Загальний кошторис -->
                <div class="mb-3">
                    <label for="budget" class="form-label">Загальний кошторис (грн)</label>
                    <input type="number" class="form-control" id="budget" name="budget" value="{{ $project->budget }}" min="0" step="0.01"> 
                </div>
                <!-- Короткий опис проєкту -->
                <div class="mb-3">
                    <label for="description" class="form-label">Короткий опис проєкту</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $project->description }}</textarea>
                </div>
                <!-- Кнопка надсилання -->
                <button type="submit" class="btn btn-primary">Зберегти зміни</button>
            </form>
        </div>
    </div>  
    @endsection
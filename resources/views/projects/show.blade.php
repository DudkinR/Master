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
            @php 
                $location = explode(',', $project->location);
                $latitude = trim($location[0]);
                $longitude = trim($location[1]);
            @endphp
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2157.4766747305384!2d{{ $longitude }}!3d{{ $latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1suk!2sua!4v1750329277888" 
                width="400" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Редагувати</a>
        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Видалити</button>
        </form>
    </div>
    <div class="mt-5">
        <h3>Завдання в рамках цього проекту</h3>
         <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                Створити нове завдання
            </button>
        <!-- Modal для створення нового завдання -->
        <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaskModalLabel">Створити нове завдання</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="mb-3 bg-dark p-3">
                                <label for="search_etalon" class="form-label text-white" style="font-weight: bold; font-size: 1.2em; color: #fff; margin-bottom: 10px;">Пошук еталона</label>
                                <input type="text" class="form-control" id="search_etalon" name="search_etalon">
                            </div>
                            <hr>
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                     
                            <div class="mb-3">
                                <label for="order" class="form-label">Порядок завдання</label>
                                <input type="number" class="form-control" id="order" name="order" min="1" required value="{{ $project->tasks->count() + 1 }}">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Назва завдання</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Опис завдання</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                           <div class="mb-3">
                                <label for="start_date" class="form-label">Дата початку</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"  value="{{ now()->format('Y-m-d') }}" >
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Дата завершення</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"  value="{{ now()->addDays(30)->format('Y-m-d') }}" >
                            </div>
                            <div class="mb-3">
                                <label for="estimated_hours" class="form-label">Орієнтовні чоловіко - години</label>
                                <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" min="0" step="0.01"  value="0">
                            </div>
                            <div class="mb-3">
                                <label for="actual_hours" class="form-label">Фактичні години</label>
                                <input type="number" class="form-control" id="actual_hours" name="actual_hours" min="0" step="0.01" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="progress" class="form-label">Прогрес (%)</label>
                                <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" step="1" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Кількість</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="0" step="1" value="0">   
                            </div>
                            <label for="unit" class="form-label">Одиниця виміру</label>
                            <select class="form-select mb-3" id="unit" name="unit" >
                                <option value="" selected disabled>Оберіть одиницю виміру</option>
                                <option value="шт">Штуки</option>
                                <option value="м">Метри</option>
                                <option value="м2">Квадратні метри</option>
                                <option value="м3">Кубічні метри</option>
                                <option value="год">Години</option>
                                <option value="т">Тони</option>
                                <option value="л">Літри</option>
                                <option value="кг">Кілограми</option>
                                <option value="шт/год">Штуки на годину</option>
                            </select>
                            </div>     
                             <div class="mb-3">
                                <label for="etalon_task" class="form-label">Взяти як еталонне завдання</label>                       
                                <input type="checkbox" class="form-check-input" id="etalon_task" name="etalon_task">
                            </div>

                            <button type="submit" class="btn btn-primary">Створити</button>
                        </form>
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
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $task->name }}</h5>
                            <p class="text-muted mb-3">{{ $task->description }}</p>

                            <div class="row row-cols-2 row-cols-md-3 g-3 small mb-3">
                                <div><strong>Порядок:</strong> {{ $task->order }}</div>
                                <div><strong>Початок:</strong> {{ $task->start_date }}</div>
                                <div><strong>Завершення:</strong> {{ $task->end_date }}</div>
                                <div><strong>Орієнтовно:</strong> {{ $task->estimated_hours }} год</div>
                                <div><strong>Фактично:</strong> {{ $task->actual_hours }} год</div>
                                <div><strong>Прогрес:</strong> {{ $task->progress }}%</div>
                                <div><strong>Кількість:</strong> {{ $task->quantity }} {{ $task->unit }}</div>
                                <div><strong>Створено:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</div>
                                <div><strong>Оновлено:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-warning btn-sm">✏️ Редагувати</a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Видалити задачу?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">🗑 Видалити</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <h3>Матеріали</h3>
                            <!-- Кнопка -->
                    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
                        ➕ Додати матеріал
                    </button>

                    <!-- Модальне вікно -->
                    <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('materials.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel-{{ $task->id }}">Новий матеріал</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Назва" required>
                                            <label for="name">Назва</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <textarea class="form-control" id="description" name="description" placeholder="Опис" style="height: 80px" required></textarea>
                                            <label for="description">Опис</label>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary w-100">💾 Зберегти</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @forelse($task->materials as $material)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $material->name }}</strong><br>
                                    <span class="text-muted">{{ $material->description }}</span><br>
                                    <small>Кількість: {{ $material->pivot->quantity }} ({{ $material->unit }})</small>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-3">
                                    <form action="{{ route('materials.update', $material->id) }}" method="POST" class="mb-1">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-outline-secondary">✏️</button>
                                    </form>
                                    <form action="{{ route('materials.destroy', $material->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">🗑</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Матеріалів поки що немає</li>
                        @endforelse
                    </ul>
                    <hr>
                    <h3>Обладнення</h3>
                    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createEquipmentModal-{{ $task->id }}">
                        ➕ Додати обладнання
                    </button>

                    <div class="modal fade" id="createEquipmentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="equipmentModalLabel-{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('equipments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="equipmentModalLabel-{{ $task->id }}">Нове обладнання</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Назва" required>
                                            <label for="name">Назва</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <textarea class="form-control" id="description" name="description" placeholder="Опис" style="height: 80px;" required></textarea>
                                            <label for="description">Опис</label>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary w-100">💾 Зберегти</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @forelse($task->equipments as $equipment)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $equipment->name }}</strong><br>
                                    <span class="text-muted">{{ $equipment->description }}</span><br>
                                    <small>Кількість: {{ $equipment->pivot->quantity }} ({{ $equipment->unit }})</small>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-3">
                                    <form action="{{ route('equipments.update', $equipment->id) }}" method="POST" class="mb-1">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-outline-secondary">✏️</button>
                                    </form>
                                    <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">🗑</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Обладнання відсутнє</li>
                        @endforelse
                    </ul>
                    <h3>Техника</h3>
                    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createTechnicModal-{{ $task->id }}">
                        ➕ Додати техніку
                    </button>
                    <div class="modal fade" id="createTechnicModal-{{ $task->id }}" tabindex="-1" aria-labelledby="technicModalLabel-{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('machines.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="technicModalLabel-{{ $task->id }}">Нова техніка</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Назва" required>
                                            <label for="name">Назва</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <textarea class="form-control" id="description" name="description" placeholder="Опис" style="height: 80px;" required></textarea>
                                            <label for="description">Опис</label>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="unit" placeholder="Одиниця" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary w-100">💾 Зберегти</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                        <ul class="list-group list-group-flush small">
                            @forelse($task->machines as $technic)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $technic->name }}</strong><br>
                                        <span class="text-muted">{{ $technic->description }}</span><br>
                                        <small>Кількість: {{ $technic->pivot->quantity }} ({{ $technic->unit }})</small>
                                    </div>
                                    <form action="{{ route('machines.update', $technic->id) }}" method="POST" class="ms-3">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">✏️</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Техніки ще не додано</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>  
         @endforeach
    </div>
    @endif
</div>
@endsection

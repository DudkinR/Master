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
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $task->name }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <p><strong>Порядок:</strong> {{ $task->order }}</p>
                                <p><strong>Дата початку:</strong> {{ $task->start_date }}</p>
                                <p><strong>Дата завершення:</strong> {{ $task->end_date }}</p>
                                <p><strong>Орієнтовні години:</strong> {{ $task->estimated_hours }} год</p>
                                <p><strong>Фактичні години:</strong> {{ $task->actual_hours }} год</p>
                                <p><strong>Прогрес:</strong> {{ $task->progress }}%</p>
                                <p><strong>Кількість:</strong> {{ $task->quantity }} {{ $task->unit }}</p>
                                <p><strong>Створено:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</p>
                                <p><strong>Оновлено:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}</p>
                                <hr>
                                <h3>Матеріали</h3>
                                 <!-- Modal для створення нового матеріалу -->
                                 <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="createMaterialModalLabel" aria-hidden="true">
                                     <div class="modal-dialog">
                                         <div class="modal-content">
                                             <div class="modal-header">
                                                 <h5 class="modal-title" id="createMaterialModalLabel">Створити новий матеріал</h5>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                 <form action="{{ route('materials.store') }}" method="POST">
                                                     @csrf
                                                     <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                     <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                     <div class="mb-3">
                                                         <label for="name" class="form-label">Назва</label>
                                                         <input type="text" class="form-control" id="name" name="name" required>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="description" class="form-label">Опис</label>
                                                         <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="quantity" class="form-label">Кількість</label>
                                                         <input type="number" class="form-control" id="quantity" name="quantity" value="0" required>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="unit" class="form-label">Одиниця виміру</label>
                                                         <input type="text" class="form-control" id="unit" name="unit" required>
                                                     </div>
                                                     <button type="submit" class="btn btn-primary">Створити</button>
                                                 </form>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
                                    Додати матеріал
                                </button>
                                <ul class="list-group">
                                    @foreach($task->materials as $material)
                                        <li class="list-group-item">
                                            <strong>{{ $material->name }}</strong> - {{ $material->description }}
                                            <span class="badge bg-primary float-end">{{ $material->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $material->pivot->quantity }}</small>
                                            <form action="{{ route('materials.update', $material->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link">Редагувати</button>
                                            </form>
                                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="float-end ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger">Видалити</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <h3>Обладнення</h3>
                                <!-- Modal для створення нового обладнання -->
                                <div class="modal fade" id="createEquipmentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="createEquipmentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createEquipmentModalLabel">Створити нове обладнання</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" >
                                                <form action="{{ route('equipments.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Назва</label>
                                                        <input type="text" class="form-control" id="name" name="name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Опис</label>
                                                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                                    </div>
                                                      <div class="mb-3">
                                                         <label for="quantity" class="form-label">Кількість</label>
                                                         <input type="number" class="form-control" id="quantity" name="quantity" value="0" required>
                                                     </div>
                                                    <div class="mb-3">
                                                        <label for="unit" class="form-label">Одиниця виміру</label>
                                                        <input type="text" class="form-control" id="unit" name="unit" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Створити</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createEquipmentModal-{{ $task->id }}">
                                    Додати обладнання
                                </button>

                                <ul class="list-group">
                                    @foreach($task->equipments as $equipment)
                                        <li class="list-group-item">
                                            <strong>{{ $equipment->name }}</strong> - {{ $equipment->description }}
                                            <span class="badge bg-secondary float-end">{{ $equipment->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $equipment->pivot->quantity }}</small>
                                            <form action="{{ route('equipments.update', $equipment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link">Редагувати</button>
                                            </form>
                                            <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST" class="float-end ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger">Видалити</button>
                                            </form>

                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <h3>Техника</h3>
                                <!-- Modal для створення нової техніки -->
                                <div class="modal fade" id="createTechnicModal-{{ $task->id }}" tabindex="-1" aria-labelledby="createTechnicModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createTechnicModalLabel">Створити нову техніку</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('machines.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                 
                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Назва</label>
                                                        <input type="text" class="form-control" id="name" name="name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Опис</label>
                                                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                                    </div>
                                                      <div class="mb-3">
                                                         <label for="quantity" class="form-label">Кількість</label>
                                                         <input type="number" class="form-control" id="quantity" name="quantity" value="0" required>
                                                     </div>
                                                    <div class="mb-3">
                                                        <label for="unit" class="form-label">Одиниця виміру</label>
                                                        <input type="text" class="form-control" id="unit" name="unit" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Створити</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTechnicModal-{{ $task->id }}">
                                    Додати техніку
                                </button>
                                <ul class="list-group">
                                    @foreach($task->machines as $technic)
                                        <li class="list-group-item">
                                            <strong>{{ $technic->name }}</strong> - {{ $technic->description }}
                                            <span class="badge bg-success float-end">{{ $technic->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $technic->pivot->quantity }}</small>
                                            
                                            <form action="{{ route('machines.update', $technic->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link">Редагувати</button>
                                            </form>

                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning">Редагувати</a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Видалити</button>
                                </form> 
                            </div>
                        </div>
                    </div>

                </div>
                    
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

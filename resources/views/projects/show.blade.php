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
                            @endif
        </div>
        @if(Auth::user()->roles->first()->name ==="Boss" || Auth::user()->roles->first()->name ==="Engineer" || Auth::user()->roles->first()->name ==="Admin")
        <div class="mt-4">
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">{{ __('Edit Project') }}</a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">{{ __('Delete Project') }}</button>
            </form>
        </div>
        @endif
    <div class="mt-5">
        <h3>{{ __('Tasks in this project') }}</h3>
            <!-- Кнопка відкриття модального вікна -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                {{ __('Create New Task Quickly') }}
            </button>
            <!-- Кнопка відкриття модального  вибора еталонного завдання -->
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#selectEtalonModal">
                {{ __('Select Reference Task') }}
            </button>

            <!-- Модальне вікно -->
            <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> {{-- Збільшений розмір --}}
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        
                       back_route
                        <input type="hidden" name="back_route" value="{{ url()->current() }}">
                     <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createTaskModalLabel">{{ __('Create New Task Quickly') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                                <div class="row g-3">
                        <div class="col-md-1">
                            <label for="order" class="form-label">{{ __('Order') }}</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ $project->tasks->count() + 1 }}" required>
                        </div>
                        <div class="col-md-11">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ now()->addDays(30)->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="estimated_hours" class="form-label">{{ __('Estimated Hours') }}</label>
                            <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" step="0.01" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="actual_hours" class="form-label">{{ __('Actual Hours') }}</label>
                            <input type="number" class="form-control" id="actual_hours" name="actual_hours" step="0.01" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="progress" class="form-label">{{ __('Progress') }} %</label>
                            <input type="number" class="form-control" id="progress" name="progress" max="100" value="0">
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" step="1" value="0">
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">{{ __('Unit') }}           </label>
                            <select class="form-select" id="unit" name="unit">
                                <option disabled selected>{{ __('Select Unit') }}   </option>
                                <option value="шт">{{ __('Pieces') }}</option>
                                <option value="м">{{ __('Meters') }}</option>
                                <option value="м2">{{ __('Square Meters') }}</option>
                                <option value="м3">{{ __('Cubic Meters') }}</option>
                                <option value="год">{{ __('Hours') }}</option>
                                <option value="т">{{ __('Tons') }}</option>
                                <option value="л">{{ __('Liters') }}</option>
                                <option value="кг">{{ __('Kilograms') }}</option>
                                <option value="шт/год">{{ __('Pieces/Hour') }}</option>
                                <option value="м/год">{{ __('Meters/Hour') }}</option>
                                <option value="комплект">{{ __('Set') }}</option>
                                
                            </select>
                        </div>

                        <div class="col-md-6" id="etalon_task_div">
                            <label for="etalon_task" class="form-label">{{ __('Ethalon Task') }}</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="etalon_task" name="etalon_task">
                                <label class="form-check-label" for="etalon_task">{{ __('Yes') }}</label>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <label for="submit_button" class="form-label">{{ __('Submit Button') }}</label>
                            <button type="submit" class="btn btn-primary w-100" id="submit_button">{{ __('Create Task') }}</button>
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

<!-- Модальне вікно -->
<div class="modal fade" id="selectEtalonModal" tabindex="-1" aria-labelledby="selectEtalonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectEtalonModalLabel">{{ __('Select Etalon Task') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="etalonSelect" class="form-label">{{ __('Select Etalon Task') }}</label>
                    <ul>
                        @foreach($etalons as $etalon)
                            <li onclick="fillingtask('{{ $etalon->id }}')" style="cursor: pointer;">
                                {{ $etalon->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const etalons = @json($etalons);

    function fillingtask(id) {
        const etalon = etalons.find(e => e.id == id);
        if (!etalon) return;

        // Заповнюємо форму
        document.getElementById('name').value = etalon.name;
        document.getElementById('description').value = etalon.description;
        document.getElementById('estimated_hours').value = etalon.estimated_hours;
        // `quantity`, `unit`
        document.getElementById('quantity').value = etalon.quantity;
        document.getElementById('unit').value = etalon.unit;
        // etalon_task_div
        document.getElementById('etalon_task_div').style.display = etalon.etalon_task ? 'block' : 'none';
        

        // Беремо обидва модальні вікна
        const selectEtalonModalEl = document.getElementById('selectEtalonModal');
        const createTaskModal = new bootstrap.Modal(document.getElementById('createTaskModal'));

        // Навішуємо подію, щоб після закриття першого відкрилось друге
        selectEtalonModalEl.addEventListener('hidden.bs.modal', function onHidden() {
            createTaskModal.show();
            // ВАЖЛИВО: прибираємо слухача, щоб не дублювався
            selectEtalonModalEl.removeEventListener('hidden.bs.modal', onHidden);
        });

        // Закриваємо першу модалку
        const selectEtalonModal = bootstrap.Modal.getInstance(selectEtalonModalEl);
        selectEtalonModal.hide();
    }
</script>


<div class="container">       
    @if($project->tasks->isEmpty())
        <div class="row">
            <div class="col-md-4 mb-4"> 
                <p>{{ __('No tasks available for this project.') }}</p>
            </div>
        </div>
    @else    
    @foreach($project->tasks as $task)
    <div class="row">
        <div class="col-md-1 mb-4">
            <p>{{ $task->order }}</p>
        </div>
        <div class="col-md-3 mb-4">
        
        <h6>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary mb-2">🔍 {{ $task->name }} </a>
        </h6>
            <p>{{ $task->description }}</p>
        </div>
        <div class="col-md-2 mb-4">
            <h6>{{ __('Materials') }}</h6>                        
            {{$task->materials->count() > 0 ? $task->materials->count() : 'Немає матеріалів'}}
        </div>
        <div class="col-md-2 mb-4">
            <h6>{{ __('Equipment') }}</h6>
            {{$task->equipments->count() > 0 ? $task->equipments->count() : 'Немає обладнання'}}
        </div>
        <div class="col-md-2 mb-4">
            <h6>{{ __('Machinery') }}</h6>
            {{$task->machines->count() > 0 ? $task->machines->count() : 'Немає техніки'}}
        </div>
        <div class="col-md-2 mb-4">
            <h6>{{ __('Scheduled Date') }}</h6>
            <p>{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') : 'Не вказано' }}</p>
            <h6>{{ __('Completion Date') }} </h6>
            <p>{{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') : 'Не вказано' }}</p>
        </div>
    </div>
    @endforeach
    
    @endif
    </div>
 </div>
</div>
@endsection

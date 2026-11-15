@extends('layouts.app')
@section('title', 'формування бригади')
@section('content')
<div class="container my-5">
    <!-- блок сповіщень -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        <br>
    @endif
    <!-- завдання -->
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

    <!-- ТМЦ -->

    <!-- обладнення -->
    <!-- Техника -->
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
                                <h3> {{ __('Materials') }} </h3>
                       
                                <ul class="list-group">
                                    @foreach($task->materials as $material)
                                        <li class="list-group-item">
                                            <strong>{{ $material->name }}</strong> - {{ $material->description }}
                                            <span class="badge bg-primary float-end">{{ $material->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $material->pivot->quantity }}</small>
                                          
                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <h3> {{ __('Equipment') }} </h3>
                           

                                <ul class="list-group">
                                    @foreach($task->equipments as $equipment)
                                        <li class="list-group-item">
                                            <strong>{{ $equipment->name }}</strong> - {{ $equipment->description }}
                                            <span class="badge bg-secondary float-end">{{ $equipment->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $equipment->pivot->quantity }}</small>
                                         
                                           

                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <h3> {{ __('Machinery') }} </h3>
                               
                                <ul class="list-group">
                                    @foreach($task->machines as $technic)
                                        <li class="list-group-item">
                                            <strong>{{ $technic->name }}</strong> - {{ $technic->description }}
                                            <span class="badge bg-success float-end">{{ $technic->unit }}</span>
                                            <small class="text-muted">Кількість: {{ $technic->pivot->quantity }}</small>
                                           

                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                        </div>
                    </div>
    <!-- Форма з одним вибором бригадира та працівників  compact('task', 'brigadirs', 'workers'));--> 
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"> {{ __('Forming a Team') }} </h5>
                    <form action="{{ route('tasks.assignBrigadir') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}" >
                        <div class="mb-3">
                            <label for="brigadir_id" class="form-label"> {{ __('Select Team Leader') }} </label>
                            <select name="brigadir_id" id="brigadir_id" class="form-select" required>
                                <option value=""> {{ __('Choose Team Leader') }} </option>
                                @foreach($brigadirs as $brigadir)
                                    <option value="{{ $brigadir->id }}"
                                    @if($task->users->contains('id', $brigadir->id)) selected @endif
                                    >{{ $brigadir->name }}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="workers" class="form-label"> {{ __('Select Workers') }} </label>
                            <select name="workers[]" id="workers" class="form-select" multiple required>
                                @foreach($workers as $worker)
                                    <option value="{{ $worker->id }}"
                                    @if($task->users->contains('id', $worker->id)) selected @endif
                                    >{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"> {{ __('Assign Team') }} </button>
                    </form>
                </div>
            </div>  
        </div>
    </div>
</div>
@endsection




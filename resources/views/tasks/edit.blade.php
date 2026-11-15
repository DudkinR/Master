@extends('layouts.app')
@section('title', 'edit task')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Інфо о завданні хто створив, до якого проекту, хто призначен майстром , бригадиром -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Task Information') }}</h5>
                    <h3>{{ $task->name }}</h3>
                    <p class="card-text">{{ $task->description }}</p>
                    <p class="card-text">
                        @if($task->projects->isNotEmpty())
                            <strong>{{ __('Project') }}:</strong> {{ $task->projects->first()->name }}<br>
                        @else
                            <strong>{{ __('Project') }}:</strong> Не призначено<br>
                        @endif
                       
                        <strong>{{ __('Created') }}:</strong> {{ $task->created_at->format('d.m.Y H:i') }}<br>
                        <strong>{{ __('Last Updated') }}:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}<br>
                        <strong>{{ __('Created By') }}:</strong> {{ $task->author->first()?->name ?? 'Невідомий' }}
                            <br>
                        <strong>{{ __('Assigned to Master') }}:</strong>  {{ $task->master->first()?->name ?? 'Не призначено' }}<br>
                        <strong>{{ __('Assigned to Crew Chief') }}:</strong> {{ $task->brigadir->first()?->name ?? 'Не призначено' }}<br>
                    </p>
                    <div class="d-flex justify-content-end">
             
                    <button class="btn btn-sm btn-outline-secondary assign-master-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#assignMasterModal">
                       👁️  Змінити
                    </button>

                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">🔙 {{ __('Back to List') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <h3>{{__('Problems')}}</h3>
            <ul class="list-group">
                @foreach($task->openProblems as $problem)
                    <li class="list-group-item">
                        <strong>{{ $problem->title }}</strong><br>
                        <span class="text-muted">{{ $problem->description }}</span>
                         @if ($problem->photos->isNotEmpty())
                            <div class="mb-2">
                                @foreach ($problem->photos as $photo)
                                    <img src="{{ $photo->url }}" alt="Problem Photo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Матеріали -->
            <h3 id="materials-section">{{ __('Materials') }}</h3>        <!-- Кнопка -->
            <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
            ➕ {{ __('Add Material') }}               </button>
            <!-- Модальне вікно -->
             <!-- якорь для перехода до цій частини сторінки -->
            <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('materials.store') }}#materials-section" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            @if($task->projects->isNotEmpty())
                                <input type="hidden" name="project_id" value="{{ $task->projects->first()->id }}">
                            @else
                                <input type="hidden" name="project_id" value="">
                            @endif
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $task->id }}">{{ __('New Material') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Name') }}" required>
                                    <label for="name">{{ __('Name') }}</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="{{ __('Description') }}" style="height: 80px" required></textarea>
                                    <label for="description">{{ __('Description') }}</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="{{ __('Quantity') }}" value="0" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}" required value="шт">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100">💾 {{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush shadow-sm rounded border">
                @forelse($task->materials as $material)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <h6 class="mb-1">{{ $material->name }}</h6>
                            <div class="text-muted small">{{ $material->description }}</div>
                            <div class="small fst-italic">
                                {{ __('Quantity') }}: <span class="fw-semibold">{{ $material->pivot->quantity }}</span> ({{ $material->unit }})
                            </div>
                        </div>
                        <div class="btn-group" role="group">
                            <form action="{{ route('materials.update', $material->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">✏️</button>
                            </form>
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this material?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted fst-italic text-center">{{ __('No materials yet') }}</li>
                @endforelse
            </ul>
            <h3 id="equipments-section">{{ __('Equipment') }}</h3>
            <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createEquipmentModal-{{ $task->id }}">
                ➕ {{ __('Add Equipment') }}
            </button>

            <div class="modal fade" id="createEquipmentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="equipmentModalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('equipments.store') }}#equipments-section" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            @if($task->projects->isNotEmpty())
                                <input type="hidden" name="project_id" value="{{ $task->projects->first()->id }}">
                            @else
                                <input type="hidden" name="project_id" value="">
                            @endif
                            <div class="modal-header">
                                <h5 class="modal-title" id="equipmentModalLabel-{{ $task->id }}">{{ __('New Equipment') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Name') }}" required>
                                    <label for="name">{{ __('Name') }}</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="{{ __('Description') }}" style="height: 80px;" required></textarea>
                                    <label for="description">{{ __('Description') }}</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="{{ __('Quantity') }}" value="0" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}" required value="шт">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100">💾  {{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush shadow-sm rounded border bg-white">
                @forelse($task->equipments as $equipment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $equipment->name }}</h6>
                            <small class="text-muted d-block">{{ $equipment->description }}</small>
                            <small class="fst-italic">{{ __('Quantity') }}: <span class="fw-semibold">{{ $equipment->pivot->quantity }}</span> ({{ $equipment->unit }})</small>
                        </div>

                        <div class="btn-group btn-group-sm" role="group">
                            <form action="{{ route('equipments.update', $equipment->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-outline-secondary" title="{{ __('Edit') }}">✏️</button>
                            </form>
                            <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити це обладнання?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="{{ __('Delete') }}">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center fst-italic">Обладнання відсутнє</li>
                @endforelse
            </ul>
            <h3 id="technics-section">Техніка</h3>
            <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createTechnicModal-{{ $task->id }}">
                ➕ Додати техніку
            </button>
            <div class="modal fade" id="createTechnicModal-{{ $task->id }}" tabindex="-1" aria-labelledby="technicModalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('machines.store') }}#technics-section" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            @if($task->projects->isNotEmpty())
                                <input type="hidden" name="project_id" value="{{ $task->projects->first()->id }}">
                            @else
                                <input type="hidden" name="project_id" value="">
                            @endif
                            <div class="modal-header">
                                <h5 class="modal-title" id="technicModalLabel-{{ $task->id }}">Нова техніка</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Name') }}" required>
                                    <label for="name">{{ __('Name') }}</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="{{ __('Description') }}" style="height: 80px;" required></textarea>
                                    <label for="description">{{ __('Description') }}</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="{{ __('Quantity') }}" value="0" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}   " required value="шт">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100">💾 {{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush shadow-sm border rounded">
                @forelse($task->machines as $technic)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $technic->name }}</h6>
                            <div class="text-muted small">{{ $technic->description }}</div>
                            <div class="small fst-italic">
                                Кількість: <span class="fw-semibold">{{ $technic->pivot->quantity }}</span> ({{ $technic->unit }})
                            </div>
                        </div>

                        <div class="btn-group btn-group-sm ms-2" role="group">
                            <form action="{{ route('machines.update', $technic->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-outline-secondary" title="{{ __('Edit') }}">✏️</button>
                            </form>
                            <form action="{{ route('machines.destroy', $technic->id) }}" method="POST" onsubmit="return confirm('Видалити техніку?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="{{ __('Delete') }}">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center fst-italic">Техніки ще не додано</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h3 class="mb-4" id="task-edit-section">🛠️ {{ __('Edit Task') }}</h3>

            <form action="{{ route('tasks.update', $task->id) }}#task-edit-section" method="POST" class="p-4 border rounded shadow-sm bg-light">
                @csrf
                @method('PUT')
                @if($task->projects->isNotEmpty())
                    <input type="hidden" name="project_id" value="{{ $task->projects->first()->id }}">
                @else
                    <input type="hidden" name="project_id" value="">
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" class="form-control" name="order" min="1" value="{{ $task->order }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ $task->name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Project') }}</label>
                        <select class="form-select" name="project_id">
                            <option value="" disabled>{{ __('Select Project') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $task->projects->contains($project) ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" rows="2">{{ $task->description }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $task->start_date}}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('End Date') }}</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $task->end_date}}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('Estimated Hours') }}</label>
                        <input type="number" step="0.01" class="form-control" name="estimated_hours" value="{{ $task->estimated_hours }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Actual Hours') }}</label>
                        <input type="number" step="0.01" class="form-control" name="actual_hours" value="{{ $task->actual_hours }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Progress') }} (%)</label>
                        <input type="number" class="form-control" name="progress" min="0" max="100" value="{{ $task->progress }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Quantity') }}</label>
                        <input type="number" class="form-control" name="quantity" step="1" value="{{ $task->quantity }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Unit of Measure') }}</label>
                        <select class="form-select" name="unit">
                            <option value="" disabled>{{ __('Select Unit') }}</option>
                            @foreach(['u','m','m2','m3','h','t','l','kg','u/h'] as $unit)
                                <option value="{{ $unit }}" {{ $task->unit === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 form-check mt-2">
                        <input type="checkbox" class="form-check-input" name="etalon_task" id="etalon_task_{{ $task->id }}" {{ $task->etalon_task ? 'checked' : '' }}>
                        <label class="form-check-label" for="etalon_task_{{ $task->id }}">{{ __('Etalon Task') }}</label>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-danger" onclick="removeItem()">🗑 {{ __('Remove') }}</button>
                    <button type="submit" class="btn btn-success px-4">💾 {{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Призначення виконавця -->
<div class="modal fade" id="assignMasterModal" tabindex="-1" aria-labelledby="assignMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="assignMasterModalLabel">{{ __('Assign Master') }}   </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label=" {{ __('Close') }}"></button>
            </div>

            <form method="POST" action="{{ route('tasks.assignMaster') }}" id="assignMasterForm">
                {{-- CSRF токен --}}
                @csrf
                <input type="hidden" name="task_id" id="modal_task_id" value="{{ $task->id }}">
                {{-- route current --}}
                <input type="hidden" name="back_route" value="{{ route('tasks.edit', $task->id) }}">


                <div class="modal-body">
                    @if(Auth::user()->isBoss())
                        <input type="hidden" name="role" value="master">
                        <div class="mb-3">
                            <label for="master_id" class="form-label">Оберіть майстра</label>
                            <select name="master_id" id="master_id" class="form-select" required>
                                <option value="">-- Виберіть --</option>
                                @foreach ($masters as $master)
                                    <option value="{{ $master->id }}">{{ $master->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    @elseif(Auth::user()->isMaster())
                        <input type="hidden" name="role" value="brigadir">
                        <div class="mb-3">
                            <label for="master_id" class="form-label">Оберіть бригадира</label>
                            <select name="master_id" id="master_id" class="form-select" required>
                                <option value="">-- Виберіть --</option>
                             @php $brigadir = $task->brigadir->first(); @endphp
                                @if($brigadirs->isNotEmpty())
                                     @foreach ($brigadirs as $brigadir)
                                        @if($brigadir)
                                            <option value="{{ $brigadir->id }}" selected>
                                                {{ $brigadir->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="" disabled>Немає доступних бригадирів</option>
                                @endif
                            </select>
                        </div>

                    @elseif(Auth::user()->isBrigadir())
                        <input type="hidden" name="role" value="worker">
                        <div class="mb-3"> 
                            <label for="master_id" class="form-label">{{ __('Select Workers') }}</label>
                            <select name="master_id[]" id="master_id" class="form-select" multiple required>
                                <option value="">-- {{ __('Select') }} --</option>
                                 
                                
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // route tasks.destroy send form method = "POST" with _method = "DELETE"
    function removeItem() {
        if (confirm('{{ __('Are you sure you want to delete this task?') }}')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('tasks.destroy', $task->id) }}';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }

    }
</script>
@endsection
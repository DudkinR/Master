@extends('layouts.app')
@section('title', 'Редагувати завдання')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Інфо о завданні хто створив, до якого проекту, хто призначен майстром , бригадиром -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Інформація про завдання</h5>
                    <p class="card-text">
                        <strong>Проект:</strong> {{ $task->projects->first()->name }}<br>
                        <strong>Створено:</strong> {{ $task->created_at->format('d.m.Y H:i') }}<br>
                        <strong>Останнє оновлення:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}<br>
                        <strong>Створив:</strong> <strong>Створив:</strong> {{ $task->author->first()?->name ?? 'Невідомий' }}
                            <br>
                        <strong>Призначено майстру:</strong>  {{ $task->master->first()?->name ?? 'Не призначено' }}<br>
                        <strong>Призначено бригадиру:</strong> {{ $task->brigadir->first()?->name ?? 'Не призначено' }}<br>
                    </p>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm me-2">👁️ Переглянути</a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">🔙 Повернутися до списку</a>
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
            <h3>Матеріали</h3>        <!-- Кнопка -->
            <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
            ➕ Додати матеріал               </button>
            <!-- Модальне вікно -->
            <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('materials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
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
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required value="шт">
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
            <ul class="list-group list-group-flush shadow-sm rounded border">
                @forelse($task->materials as $material)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <h6 class="mb-1">{{ $material->name }}</h6>
                            <div class="text-muted small">{{ $material->description }}</div>
                            <div class="small fst-italic">
                                Кількість: <span class="fw-semibold">{{ $material->pivot->quantity }}</span> ({{ $material->unit }})
                            </div>
                        </div>
                        <div class="btn-group" role="group">
                            <form action="{{ route('materials.update', $material->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Редагувати">✏️</button>
                            </form>
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цей матеріал?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Видалити">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted fst-italic text-center">Матеріалів поки що немає</li>
                @endforelse
            </ul>
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
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
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
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required value="шт">
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
            <ul class="list-group list-group-flush shadow-sm rounded border bg-white">
                @forelse($task->equipments as $equipment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $equipment->name }}</h6>
                            <small class="text-muted d-block">{{ $equipment->description }}</small>
                            <small class="fst-italic">Кількість: <span class="fw-semibold">{{ $equipment->pivot->quantity }}</span> ({{ $equipment->unit }})</small>
                        </div>

                        <div class="btn-group btn-group-sm" role="group">
                            <form action="{{ route('equipments.update', $equipment->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-outline-secondary" title="Редагувати">✏️</button>
                            </form>
                            <form action="{{ route('equipments.destroy', $equipment->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити це обладнання?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Видалити">🗑️</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center fst-italic">Обладнання відсутнє</li>
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
                            <input type="hidden" name="project_id" value="{{ $task->projects->first()->id  }}">
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
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required value="шт">
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
                                <button type="submit" class="btn btn-outline-secondary" title="Редагувати">✏️</button>
                            </form>
                            <form action="{{ route('machines.destroy', $technic->id) }}" method="POST" onsubmit="return confirm('Видалити техніку?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Видалити">🗑️</button>
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
            <h3 class="mb-4">🛠️ Редагування завдання</h3>

            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
                @csrf
                @method('PUT')
                <input type="hidden" name="project_id" value="{{ $task->projects->first()->id }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Порядок</label>
                        <input type="number" class="form-control" name="order" min="1" value="{{ $task->order }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Назва</label>
                        <input type="text" class="form-control" name="name" value="{{ $task->name }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Опис</label>
                        <textarea class="form-control" name="description" rows="2">{{ $task->description }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Дата початку</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $task->start_date}}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Дата завершення</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $task->end_date}}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Оцінка годин</label>
                        <input type="number" step="0.01" class="form-control" name="estimated_hours" value="{{ $task->estimated_hours }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Фактичні години</label>
                        <input type="number" step="0.01" class="form-control" name="actual_hours" value="{{ $task->actual_hours }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Прогрес (%)</label>
                        <input type="number" class="form-control" name="progress" min="0" max="100" value="{{ $task->progress }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Кількість</label>
                        <input type="number" class="form-control" name="quantity" step="1" value="{{ $task->quantity }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Одиниця виміру</label>
                        <select class="form-select" name="unit">
                            <option value="" disabled>Оберіть одиницю</option>
                            @foreach(['шт','м','м2','м3','год','т','л','кг','шт/год'] as $unit)
                                <option value="{{ $unit }}" {{ $task->unit === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 form-check mt-2">
                        <input type="checkbox" class="form-check-input" name="etalon_task" id="etalon_task_{{ $task->id }}" {{ $task->etalon_task ? 'checked' : '' }}>
                        <label class="form-check-label" for="etalon_task_{{ $task->id }}">Еталонне завдання</label>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-danger" onclick="removeItem()">🗑 Прибрати</button>
                    <button type="submit" class="btn btn-success px-4">💾 Зберегти</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // route tasks.destroy send form method = "POST" with _method = "DELETE"
    function removeItem() {
        if (confirm('Ви впевнені, що хочете видалити це завдання?')) {
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
@extends('layouts.app')
@section('title', 'Завдання')
@section('content')
<div class="container my-5">

    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <h4 class="mb-3">🔧 Завдання, які очікують виконання</h4>

    @forelse ($tasks as $task)
        @php
            $statusMap = [
                'completed' => ['Виконано', 'success'],
                'in_progress' => ['В процесі', 'warning text-dark'],
                'pending' => ['Не розпочато', 'secondary'],
            ];
            [$label, $badge] = $statusMap[$task->status] ?? ['Невідомо', 'dark'];
        @endphp

        <div class="card border-start border-4 border-{{ $badge }} mb-3 shadow-sm">
            <div class="card-body" data-bs-toggle="modal" data-bs-target="#taskDetailsModal{{ $task->id }}" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $task->name }}</h5>
                    <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                </div>
                <p class="mb-1"><strong>Майстер:</strong> {{ optional($task->master->first())->name ?? '—' }}</p>
                <p class="mb-1"><strong>Бригадир:</strong> {{ optional($task->brigadir->first())->name ?? '—' }}</p>
                <p class="mb-1"><strong>Робітники:</strong>
                    @forelse ($task->workers as $worker)
                        {{ $worker->name }}@if (!$loop->last), @endif
                    @empty
                        Не призначено
                    @endforelse
                </p>
                <small class="text-muted">Початок: {{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') }} | Закінчення: {{ \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') }}</small>
            </div>
        </div>

        {{-- Модальне вікно деталей завдання --}}
        <div class="modal fade" id="taskDetailsModal{{ $task->id }}" tabindex="-1" aria-labelledby="taskDetailsModalLabel{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-sm">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $task->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Опис:</strong> {{ $task->description }}</p>
                        <p><strong>Порядок:</strong> {{ $task->order }}</p>
                        <p><strong>Дата початку:</strong> {{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') }}</p>
                        <p><strong>Дата завершення:</strong> {{ \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') }}</p>
                        <p><strong>Орієнтовні години:</strong> {{ $task->estimated_hours }} год</p>
                        <p><strong>Кількість / одиниця:</strong> {{ $task->quantity }} {{ $task->unit }}</p>
                        <p><strong>Статус:</strong> <span class="badge bg-{{ $badge }}">{{ $label }}</span></p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-primary">Перейти до карточки</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                    </div>
                </div>
            </div>
        </div>

    @empty
        <div class="alert alert-info">Немає актуальних завдань для виконання.</div>
    @endforelse
</div>

<!-- Modal: Призначення виконавця -->
<div class="modal fade" id="assignMasterModal" tabindex="-1" aria-labelledby="assignMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="assignMasterModalLabel">Призначення виконавця</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
            </div>

            <form method="POST" action="{{ route('tasks.assignMaster') }}">
                @csrf
                <input type="hidden" name="task_id" id="modal_task_id" value=""> {{-- буде оновлено JS --}}

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
                                @foreach ($brigadirs as $brigadir)
                                    <option value="{{ $brigadir->id }}">{{ $brigadir->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    @elseif(Auth::user()->isBrigadir())
                        <input type="hidden" name="role" value="worker">
                        <div class="mb-3">
                            <label for="master_id" class="form-label">Оберіть робітників</label>
                            <select name="master_id[]" id="master_id" class="form-select" multiple required>
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Призначити</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Скасувати</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function set_task_id(task_id) {
    // Знаходимо  <input type="hidden" name="task_id" value="{{ $task->id }}">
    const taskIdInput = document.querySelector('input[name="task_id"]');
    if (taskIdInput) {
        taskIdInput.value = task_id; // Встановлюємо значення task_id
    } else {
        console.error('Не знайдено input з ім\'ям "task_id"');
    }
}
</script>

@endsection

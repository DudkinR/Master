@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
<div class="container my-5">

    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <h4 class="mb-3">🔧 {{ __('Tasks Awaiting Execution') }}</h4>

    @forelse ($tasks as $task)
        @php
            $statusMap = [
                'completed' => ['{{ __('Completed') }}', 'success'],
                'paused' => ['{{ __('Paused') }}', 'warning text-dark'],
                'day_work' => ['{{ __('Work Day') }}', 'primary'],
                'day_off' => ['{{ __('Off Day') }}', 'secondary'],    
                'end_day' => ['{{ __('End of Work Day') }}', 'danger'],
                'end_task' => ['{{ __('Task Completed') }}', 'success'],
                'issue' => ['{{ __('Issue') }}', 'danger'],
                'missing_resource' => ['{{ __('Missing Resource') }}', 'danger'],
                'in_work' => ['{{ __('In Work') }}', 'info'],
                'in_progress' => ['{{ __('In Progress') }}', 'warning text-dark'],
                'pending' => ['{{ __('Pending') }}', 'secondary'],
            ];
            [$label, $badge] = $statusMap[$task->status] ?? ['{{ __('Unknown') }}', 'dark'];
        @endphp

        <div class="card border-start border-4 border-{{ $badge }} mb-3 shadow-sm">
            <div class="card-body" data-bs-toggle="modal" data-bs-target="#taskDetailsModal{{ $task->id }}" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $task->name }}</h5>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($task->photos->isNotEmpty())
                                <div class="mb-2">
                                    @foreach ($task->photos as $photo)
                                        <img src="{{ $photo->url }}" alt="Task Photo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                </div> 
                <p class="mb-1"><strong>{{ __('Master') }}:</strong> {{ optional($task->master->first())->name ?? '—' }}</p>
                <p class="mb-1"><strong>{{ __('Brigadier') }}:</strong> {{ optional($task->brigadir->first())->name ?? '—' }}</p>
                <p class="mb-1"><strong>{{ __('Workers') }}:</strong>
                    @forelse ($task->workers as $worker)
                        {{ $worker->name }}@if (!$loop->last), @endif
                    @empty
                        Не призначено
                    @endforelse
                </p>
                <small class="text-muted">{{ __('Start Date') }}: {{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') }} | {{ __('End Date') }}: {{ \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') }}</small>
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
                        <p><strong>{{ __('Description') }}:</strong> {{ $task->description }}</p>
                        <p><strong>{{ __('Order') }}:</strong> {{ $task->order }}</p>
                        <p><strong>{{ __('Start Date') }}:</strong> {{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') }}</p>
                        <p><strong>{{ __('End Date') }}:</strong> {{ \Carbon\Carbon::parse($task->end_date)->format('d.m.Y') }}</p>
                        <p><strong>{{ __('Estimated Hours') }}:</strong> {{ $task->estimated_hours }} {{ __('hours') }}</p>
                        <p><strong>{{ __('Quantity / Unit') }}:</strong> {{ $task->quantity }} {{ $task->unit }}</p>
                        <p><strong>{{ __('Status') }}:</strong> <span class="badge bg-{{ $badge }}">{{ $label }}</span></p>
                       
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-primary">{{ __('Go to Card') }}</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

    @empty
        <div class="alert alert-info">{{ __('No tasks available for execution.') }}</div>
    @endforelse
</div>

<!-- Modal: Призначення виконавця -->
<div class="modal fade" id="assignMasterModal" tabindex="-1" aria-labelledby="assignMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="assignMasterModalLabel"> {{ __('Assign Master') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label=" {{ __('Close') }}"></button>
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
                            <label for="master_id" class="form-label"> {{ __('Select Workers') }}   </label>
                            <select name="master_id[]" id="master_id" class="form-select" multiple required>
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"> {{ __('Assign') }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> {{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function set_task_id(task_id) {
    const taskIdInput = document.querySelector('input[name="task_id"]');
    if (taskIdInput) {
        taskIdInput.value = task_id; // Встановлюємо значення task_id
    } else {
        console.error('don\'t find input with name "task_id"');
    }
}
</script>

@endsection

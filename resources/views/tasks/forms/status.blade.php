<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        {{-- Статус завдання --}}
        @if ($task->status === 'completed')
            <span class="badge w-100 bg-success">✅ Завершено</span>
        @elseif ($task->status === 'in_progress')
            <span class="badge w-100 bg-warning text-dark">🛠 У процесі</span>
        @elseif ($task->status === 'paused')
            <span class="badge w-100 bg-info text-dark">⏸ На паузі</span>
        @elseif ($task->status === 'not_started')
            <span class="badge w-100 bg-secondary">❌ Не розпочато</span>
        @elseif ($task->status === 'cancelled')
            <span class="badge w-100 bg-danger">❌ Скасовано</span>
        @elseif ($task->status === 'waiting')
            <span class="badge w-100 bg-secondary">⏳ Очікує</span>
        @elseif ($task->status === 'issue')
            <span class="badge w-100 bg-danger">🚨 Проблема</span
        @elseif ($task->status === 'delayed')
            <span class="badge w-100 bg-danger">⏳ Затримка</span>
        @elseif ($task->status === 'on_hold')
            <span class="badg w-100 bg-secondary">⏸ На утриманні</span>
        @elseif ($task->status === 'in_review')
            <span class="badge w-100 bg-primary">🔍 На розгляді</span
        @elseif ($task->status === 'rejected')
            <span class="badge w-100 bg-danger">❌ Відхилено</span>
        @elseif ($task->status === 'approved')
            <span class="badge w-100 bg-success">✅ Затверджено</span>    
        @elseif ($task->status === 'completed')    
            <span class="badge w-100 bg-success">✅ Завершено</span>
        @else
            <span class="badge w-100 bg-secondary">🕒 Очікує</span>
        @endif
        {{-- Остання дія користувача --}}
        @if ($lastAction)
            @php
                $actionLabels = [
                    'start' => 'Початок',
                    'pause' => 'Перерва',
                    'resume' => 'Продовження',
                    'end_day' => 'Завершення дня',
                    'complete' => 'Завершено',
                    'issue' => 'Проблема',
                ];
            @endphp
        @else
            <span class="badge bg-secondary">❌ Ще не розпочато</span>
        @endif
    </div>
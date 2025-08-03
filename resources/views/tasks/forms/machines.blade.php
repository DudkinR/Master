 <div class="mb-3">
        <label class="form-label fw-bold">Наявність техніки:</label><br>
        @foreach ($task->machines as $machine)
            <div class="form-check form-check-inline">              
                <label class="form-check-label" for="machine_{{$machine->id}}">{{ $machine->name }}</label>
                {{-- buttons для техніки  початок роботи, перерва, закінчення таймер роботи --}}
                <button id="start_machine_{{$machine->id}}" 
                 class="btn btn-outline-secondary btn-sm" onClick="startMachine({{ $machine->id }} ,{{ $task->id }})">
                    ▶ Почати роботу
                </button>
                <button id="pause_machine_{{$machine->id}}"
                 class="btn btn-outline-warning btn-sm d-none" onClick="pauseMachine({{ $machine->id }},{{ $task->id }})">
                    ⏸ Перерва
                </button>
                <button id="end_machine_{{$machine->id}}"
                 class="btn btn-outline-danger btn-sm d-none" onClick="endMachine({{ $machine->id }},{{ $task->id }})">
                    ⏹ Завершити
                </button>
                {{-- Timer --}} 
                <span class="badge bg-info ms-2" id="machineTimerDisplay-{{ $machine->id }}">00:00:00</span>
                
            </div>
        @endforeach
</div>
<button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createTechnicModal-{{ $task->id }}">
    ➕ Додати техніку
</button>
<div class="modal fade" id="createTechnicModal-{{ $task->id }}" tabindex="-1" aria-labelledby="technicModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('machines.store') }}" method="POST">
                @csrf
                <input type="hidden" name="views" value="showtask">
                <input type="hidden" name="task_id" value="{{ $task->id }}">
               
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
                            <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="1" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="unit" placeholder="Одиниця" required  value="шт">
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
<script>
  
</script>
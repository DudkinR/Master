@extends('layouts.app')
@section('title', 'Завдання')
@section('content')
<div class="container my-5">
    {{-- Сповіщення --}}
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif
    {{-- with('success' --}}
    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    {{-- create new task button w-100 success --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <a type="button"  class="btn btn-success w-100"  href="{{ route('tasks.create') }}" style="text-decoration: none;">
                {{ __('Create New Task') }}
            </a>
        </div>
    </div>
    <div class="container" id="tasksContainer">
    {{-- Завдання --}}
     </div>   
        {{-- Модальне вікно деталей завдання --}}
    <div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailsModalLabel"> {{ __('Task Details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label=" {{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p><strong> {{ __('Description') }}:</strong> <span id="taskDescription"></span></p>
                    <p><strong> {{ __('Order') }}:</strong> <span id="taskOrder"></span></p>
                    <p><strong> {{ __('Start Date') }}:</strong> <span id="taskStartDate"></span></p>
                    <p><strong> {{ __('End Date') }}:</strong> <span id="taskEndDate"></span></p>
                    <p><strong> {{ __('Estimated Hours') }}:</strong> <span id="taskEstimatedHours"></span> {{ __('hours') }}</p>
                    <p><strong> {{ __('Status') }}:</strong>
                    <span class="badge" id="taskStatusLabel"></span>
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-between mt-4">
                   <a href="" id="repeatTaskLink" class="btn btn-success">
                    {{__('Repeat')}}
                   </a>
                   <a href="" id="showTaskLink" class="btn btn-primary">
                   {{__('Show')}}
                     </a>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> {{ __('Close') }}</button>

                </div>
            </div>
        </div>
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
                            <label for="master_id" class="form-label"> {{ __('Select Master') }}</label>
                            <select name="master_id" id="master_id" class="form-select" required>
                                <option value="">-- {{ __('Select') }} --</option>
                                @foreach ($masters as $master)
                                    <option value="{{ $master->id }}">{{ $master->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    @elseif(Auth::user()->isMaster())
                        <input type="hidden" name="role" value="brigadir">
                        <div class="mb-3">
                            <label for="master_id" class="form-label"> {{ __('Select Brigadir') }}</label>
                            <select name="master_id" id="master_id" class="form-select" required>
                                <option value="">-- {{ __('Select') }} --</option>
                                @foreach ($brigadirs as $brigadir)
                                    <option value="{{ $brigadir->id }}">{{ $brigadir->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    @elseif(Auth::user()->isBrigadir())
                        <input type="hidden" name="role" value="worker">
                        <div class="mb-3"> 
                            <label for="master_id" class="form-label"> {{ __('Select Workers') }}</label>
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
    // заповнення карток завдань
  document.addEventListener('DOMContentLoaded', function () {
    const tasks = @json($tasks);

    const tasksContainer = document.getElementById('tasksContainer');

    tasks.forEach(task => {
        const card = document.createElement('div');
        card.className = 'card mb-3 shadow-sm';
       
        const masterName = task.master[0]?.name ??  'not assigned';
        const brigadirName = task.brigadir[0]?.name ?? 'not assigned';
        const workers = Array.isArray(task.workers) && task.workers.length > 0
            ? task.workers.map(worker => worker.name).join(', ')
            : 'not assigned';
        const masterClass = masterName === 'not assigned' ? 'text-light' : '';
        const masterClassBg = masterName === 'not assigned' ? 'bg-danger' : '';
        const brigadirClass = brigadirName === 'not assigned' ? 'text-light' : '';
        const brigadirClassBg = brigadirName === 'not assigned' ? 'bg-danger' : '';
        const workersClass = workers === 'not assigned' ? 'text-light' : '';
        const workersClassBg = workers === 'not assigned' ? 'bg-danger' : '';
        const statusMap = {
            pending: ['Waiting', 'bg-secondary'],
            in_progress: ['In Progress', 'bg-warning'],
            completed: ['Completed', 'bg-success'],
            paused: ['Paused', 'bg-info'],
            end_day: ['End of Day', 'bg-primary'],
            start_day: ['Start of Day', 'bg-success'],
            not_completed: ['Not Completed', 'bg-danger'],
            test: ['Test', 'bg-info'],
        };
        const [statusLabel, statusClass] = statusMap[task.status] ?? ['Unknown', 'bg-dark'];

        const progressHtml = task.progress !== null
            ? `<div class="progress" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: ${task.progress}%;"
                        aria-valuenow="${task.progress}" aria-valuemin="0" aria-valuemax="100">
                        ${task.progress}%
                    </div>
               </div>`
            : '<span class="text-muted">Not Defined</span>';

        card.innerHTML = `
            <div class="card-body task-item"
                data-id="${task.id}"
                data-name="${task.name}"
                data-description="${task.description}"
                data-order="${task.order}"
                data-start-date="${task.start_date}"
                data-end-date="${task.end_date}"
                data-estimated-hours="${task.estimated_hours}"
                data-status="${task.status}"
                data-bs-toggle="modal"
                data-bs-target="#taskDetailsModal"
                style="cursor: pointer;">
                <h5 class="card-title">${task.name}</h5>
                <p class="mb-1"><strong>Початок:</strong> ${task.start_date}</p>
                <p class="mb-1"><strong>Кінець:</strong> ${task.end_date}</p>
                <p class="mb-1"><strong>Орієнтовні години:</strong> ${task.estimated_hours} год</p>
                <p class="mb-1"><strong>Статус:</strong>
                    <span class="badge ${statusClass}">${statusLabel}</span>
                </p>
                <div class="
                            ${masterClassBg}
                            ${brigadirClassBg}
                            ${workersClassBg} p-2 rounded mb-2">
                <p class="mb-1 ${masterClass}"><strong>Майстер:</strong> ${masterName}</p>
                <p class="mb-1 ${brigadirClass}"><strong>Бригадир:</strong> ${brigadirName}</p>
                <p class="mb-1  ${workersClass}"><strong>Робітники:</strong> ${workers}</p>
                </div>
                <div class="mt-2 text-end">
                    <button class="btn btn-sm btn-outline-secondary assign-master-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#assignMasterModal"
                            data-task-id="${task.id}"
                            onclick="set_task_id(${task.id})"
                            data-action="/tasks/assign/${task.id}">
                        {{ __('Change') }}
                    </button>
                </div>
            </div>
        `;
        tasksContainer.appendChild(card);
    });
});  
// зробити сортіровку  перши назначени сьогодні завдання, потім ті що на завтра, потім по процетному виконанню
document.addEventListener('DOMContentLoaded', function () {
    const tasksContainer = document.getElementById('tasksContainer');
    const taskItems = document.querySelectorAll('.task-item');
    const showTaskLink = document.getElementById('showTaskLink');
    // Заповнення модального вікна при натисканні на картку
    taskItems.forEach(item => {
        item.addEventListener('click', function () {
            console.log('Task clicked:', this.dataset.id);
            document.getElementById('taskDetailsModalLabel').innerText = this.dataset.name;
            document.getElementById('taskDescription').innerText = this.dataset.description;
            document.getElementById('taskOrder').innerText = this.dataset.order;
            document.getElementById('taskStartDate').innerText = this.dataset.startDate;
            document.getElementById('taskEndDate').innerText = this.dataset.endDate;
            document.getElementById('taskEstimatedHours').innerText = this.dataset.estimatedHours; 
            // showTaskLink
            document.getElementById('showTaskLink').href = `/tasks/${this.dataset.id}`;          
         
            
         });
        });
    });

    // 🧠 Сортування завдань (сьогодні → завтра → інше, потім % виконання)
function sortTasks() {
        const cards = Array.from(tasksContainer.querySelectorAll('.card'));
        const now = new Date().toISOString().slice(0, 10); // YYYY-MM-DD

        cards.sort((a, b) => {
            const aDate = a.querySelector('.task-item').dataset.startDate || '9999-12-31';
            const bDate = b.querySelector('.task-item').dataset.startDate || '9999-12-31';

            // Сьогоднішні перші
            if (aDate === now && bDate !== now) return -1;
            if (bDate === now && aDate !== now) return 1;

            // Завтра другі
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().slice(0, 10);

            if (aDate === tomorrowStr && bDate !== tomorrowStr) return -1;
            if (bDate === tomorrowStr && aDate !== tomorrowStr) return 1;

            // Інакше сортуємо за датою
            return aDate.localeCompare(bDate);
        });

        // Перемальовуємо
        tasksContainer.innerHTML = '';
        cards.forEach(card => tasksContainer.appendChild(card));
    }

    sortTasks();

function set_task_id(task_id) {
    const taskIdInput = document.querySelector('input[name="task_id"]');
    if (taskIdInput) {
        taskIdInput.value = task_id; // Встановлюємо значення task_id
    } else {
        console.error('Не знайдено input з ім\'ям "task_id"');
    }
}

    // Додаємо оброботку tasks
    document.querySelectorAll('.assign-master-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.dataset.taskId;
            const actionUrl = this.dataset.action.replace(':id', taskId);
            const modal = document.getElementById('assignMasterModal');
            modal.querySelector('form').action = actionUrl;
            modal.querySelector('input[name="task_id"]').value = taskId;
        });
    });
    //route repeat_task method get
    document.getElementById('repeatTaskLink').addEventListener('click', function(event) {
        event.preventDefault();
        const taskId = document.querySelector('.task-item').dataset.id;
        window.location.href = `/task_repeat/${taskId}`;
    });
</script>

@endsection

<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <span id="taskStatus" class="badge w-100"></span>
    <span id="lastActionStatus" class="badge w-100"></span>
</div>

<!-- Передача значень у data-атрибутах -->
<div id="taskData"
     data-status="{{ $task->status }}"
     data-last-action="{{ $lastAction->action_type ?? '' }}">
</div>


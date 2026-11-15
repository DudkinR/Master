<!-- components/create-resource-modal.blade.php -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $labelId }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ $route }}" method="POST">
        @csrf
        <input type="hidden" name="views" value="showtask">
        <input type="hidden" name="task_id" value="{{ $taskId }}">
        <input type="hidden" name="project_id" value="{{ $projectId }}">
        <div class="modal-header">
          <h5 class="modal-title" id="{{ $labelId }}">{{ $title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <x-form.input name="name" label="Назва" required />
          <x-form.textarea name="description" label="Опис" required />
          <div class="row g-2 mb-2">
            <div class="col-6">
              <x-form.input name="quantity" label="Кількість" type="number" value="0" required />
            </div>
            <div class="col-6">
              <x-form.input name="unit" label="Одиниця" required />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary w-100">💾
            {{ __('Save') }}
            </button>

        </div>
      </form>
    </div>
  </div>
</div>

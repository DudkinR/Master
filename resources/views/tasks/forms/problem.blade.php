<div class="modal fade" id="problemModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('tasks.issue', $task->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="views" value="showtask">
            <input type="hidden" name="task_id" value="{{ $task->id }}">

            <div class="modal-header">
                <h5 class="modal-title">Проблема</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="title" placeholder="Заголовок проблеми" required>
                    <label for="title">Заголовок проблеми</label>
                </div>
                <label for="description" class="form-label">Опис проблеми</label>
                <textarea class="form-control" name="description" placeholder="Опишіть проблему..." rows="4" required></textarea>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="photo_problem_checkbox" id="photoProblem">
                    <label class="form-check-label" for="photoProblem">Додати фото-звіт</label>
                </div>

                <div class="mt-3" id="photoProblemSection" style="display: none;">
                    <label for="photo_problem" class="form-label">Фото-звіт</label>
                    <input 
                        type="file" 
                        name="photo_problem[]" 
                        class="form-control" 
                        multiple 
                        accept="image/*" 
                        capture="environment"
                    >
                    <small class="form-text text-muted">
                        Ви можете додати декілька фото-звітів. Максимальний розмір файлу 5 МБ.
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Надіслати</button>
            </div>

            <div class="modal-footer flex-column">
                @foreach ($problems as $problem)
                    @if($problem->status === 'open')
                    <div class="alert alert-danger mb-2 w-100">
                        <h6 class="alert-heading">{{ $problem->title }}</h6>
                        <p>{{ $problem->description }}</p>
                        @if ($problem->photos->isNotEmpty())
                            <div class="mb-2">
                                @foreach ($problem->photos as $photo)
                                    <img src="{{ $photo->url }}" alt="Problem Photo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach

                @if ($problems->isEmpty())
                    <div class="alert alert-info w-100">Проблем немає.</div>
                @endif
            </div>
        </form>
    </div>
</div>


<script>
  document.getElementById('photoProblem').addEventListener('change', function() {
    document.getElementById('photoProblemSection').style.display = this.checked ? 'block' : 'none'; 
});

</script>
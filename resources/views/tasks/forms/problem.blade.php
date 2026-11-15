<div class="modal fade" id="problemModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('tasks.issue', $task->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="views" value="showtask">
            <input type="hidden" name="task_id" value="{{ $task->id }}">

            <div class="modal-header">
                <h5 class="modal-title"> {{ __('Problem') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="title" placeholder="{{ __('Problem Title') }}" required>
                    <label for="title">{{ __('Problem Title') }}</label>
                </div>
                <label for="description" class="form-label"> {{ __('Description') }}</label>
                <textarea class="form-control" name="description" placeholder="{{ __('Describe the problem...') }}" rows="4" required></textarea>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="photo_problem_checkbox" id="photoProblem">
                    <label class="form-check-label" for="photoProblem"> {{ __('Add Photo Report') }}</label>
                </div>

                <div class="mt-3" id="photoProblemSection" style="display: none;">
                    <label for="photo_problem" class="form-label"> {{ __('Photo Report') }}</label>
                    <input 
                        type="file" 
                        name="photo_problem[]" 
                        class="form-control" 
                        multiple 
                        accept="image/*" 
                        capture="environment"
                        id="photoInput"
                    >
                    <!-- Замінюємо старий input на прихований, який відправиться на сервер -->
                    <input type="hidden" name="resized_photos[]" id="resizedPhotosPlaceholder">

                    <small class="form-text text-muted">
                        {{ __('You can add multiple photo reports. Maximum file size is 5 MB.') }}
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">{{ __('Submit') }} </button>
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
                    <div class="alert alert-info w-100"> {{ __('No problems available.') }} </div>
                @endif
            </div>
        </form>
    </div>
</div>


<script>
  document.getElementById('photoProblem').addEventListener('change', function() {
    document.getElementById('photoProblemSection').style.display = this.checked ? 'block' : 'none'; 
});
document.getElementById('photoInput').addEventListener('change', async function (event) {
    const files = Array.from(event.target.files);
    const resizedFiles = [];

    for (const file of files) {
        if (!file.type.startsWith('image/')) continue;

        const resized = await resizeImage(file, 1024, 768); // Максимальні розміри
        resizedFiles.push(resized);
    }

    // Створюємо новий input type="file" через DataTransfer (для надсилання форми)
    const dt = new DataTransfer();
    resizedFiles.forEach(file => dt.items.add(file));

    event.target.files = dt.files; // замінюємо original input.files
});

</script>
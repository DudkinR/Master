<div class="modal fade" id="endWorkModal" tabindex="-1" aria-labelledby="endWorkModalLabel">
    @csrf
    <div class="modal-dialog">
        <form class="modal-content" id="endWorkForm">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Finish Work') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="progress" class="form-label">{{ __('Progress') }}</label>
                <input type="number" name="progress" class="form-control" min="0" max="100" step="1" 
                 id="progress"
                value="{{ ceil($task->progress) ?? 0 }}" placeholder="{{ __('Enter completion percentage') }}" 
                 required>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="photo_report" id="photoReport">
                    <label class="form-check-label" for="photoReport">{{ __('Add Photo Report') }}</label>
                </div>
            </div>
            <div class="modal-body" id="photoReportSection" style="display: none;">
                <label for="photo_report" class="form-label">{{ __('Photo Report') }}</label>
                <input 
                    type="file" 
                    name="photo_report[]" 
                    class="form-control" 
                    multiple 
                    accept="image/*" 
                    capture="environment"
                    id="photoInput_end_work"
                >
                <small class="form-text text-muted">
                    {{ __('You can add multiple photo reports. Maximum file size is 5 MB.') }}
                </small>
            </div>
            <div class="modal-body">
                <label for="comment" class="form-label">{{ __('Comment') }}</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="{{ __('Add a comment to the completed work') }}"></textarea>
            </div>
            <div class="modal-body">

                <div class="form-check">
                    <input type="radio" name="end_day" id="end_day1" value="end_day" class="form-check-input" checked>
                    <label class="form-check-label" for="end_day1">{{ __('End of Work Day') }}</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="end_day" id="end_day2" value="completed" class="form-check-input">
                    <label class="form-check-label" for="end_day2">{{ __('Complete Task 100%') }}</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">{{ __('Confirm Completion') }}</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('photoReport').addEventListener('change', function() {
        document.getElementById('photoReportSection').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('endWorkForm').addEventListener('submit', function(event) {
        event.preventDefault();
        // Тут можна додати AJAX запит для відправки даних на сервер
        alert('{{ __('Work day completed!') }}');
       bootstrap.Modal.getInstance(document.getElementById('endWorkModal')).hide();

    });
    document.getElementById('photoInput_end_work').addEventListener('change', async function (event) {
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
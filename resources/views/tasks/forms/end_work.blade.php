<div class="modal fade" id="endWorkModal" tabindex="-1" aria-labelledby="endWorkModalLabel">
    @csrf
    <div class="modal-dialog">
        <form class="modal-content" id="endWorkForm">
            <div class="modal-header">
                <h5 class="modal-title">Завершити роботу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="progress" class="form-label">Виконано, %</label>
                <input type="number" name="progress" class="form-control" min="0" max="100" step="1" 
                 id="progress"
                value="{{ ceil($task->progress) ?? 0 }}" placeholder="Введіть відсоток виконання" 
                 required>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="photo_report" id="photoReport">
                    <label class="form-check-label" for="photoReport">Додати фото-звіт</label>
                </div>
            </div>
            <div class="modal-body" id="photoReportSection" style="display: none;">
                <label for="photo_report" class="form-label">Фото-звіт</label>
                <input 
                    type="file" 
                    name="photo_report[]" 
                    class="form-control" 
                    multiple 
                    accept="image/*" 
                    capture="environment"
                >
                <small class="form-text text-muted">
                    Ви можете додати декілька фото-звітів. Максимальний розмір файлу 5 МБ.
                </small>
            </div>
            <div class="modal-body">
                <label for="comment" class="form-label">Коментар</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Додайте коментар до завершення робочого дня"></textarea>
            </div>
            <div class="modal-body">

                <div class="form-check">
                    <input type="radio" name="end_day" id="end_day1" value="end_day" class="form-check-input" checked>
                    <label class="form-check-label" for="end_day1">Кінець робочого дня</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="end_day" id="end_day2" value="completed" class="form-check-input">
                    <label class="form-check-label" for="end_day2">Завершити завдання 100%</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Підтвердити завершення</button>
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
        alert('Робочий день завершено!');
       bootstrap.Modal.getInstance(document.getElementById('endWorkModal')).hide();

    });
</script>
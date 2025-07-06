<div class="modal fade" id="endWorkModal" tabindex="-1" aria-labelledby="endWorkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tasks.complete', $task->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Завершити роботу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="progress" class="form-label">Виконано, %</label>
                    <input type="number" name="progress" class="form-control" min="0" max="100" required>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="photo_report" id="photoReport">
                        <label class="form-check-label" for="photoReport">Додати фото-звіт</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Підтвердити завершення</button>
                </div>
            </form>
        </div>
    </div>
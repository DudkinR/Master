 <div class="modal fade" id="problemModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tasks.report_issue', $task->id) }}">
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
                    <textarea class="form-control" name="description"  placeholder="Опишіть проблему..." rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Надіслати</button>
                </div>
                <div class="modal-footer">
                    
                    @foreach ($problems as $problem)
                        <div class="alert alert-danger mb-2">
                            <h6 class="alert-heading">{{ $problem->title }}</h6>
                            <p>{{ $problem->description }}</p>
                        </div>
                    @endforeach
                    @if ($problems->isEmpty())
                        <div class="alert alert-info">Проблем немає.</div>
                    @endif

                </div>
            </form>
        </div>
    </div>
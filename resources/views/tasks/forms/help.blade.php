  <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> {{ __('Help') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><strong>▶ {{ __('Start Work') }}</strong> —  {{ __('starts the timer and records the start.') }}</li>
                        <li><strong>⏸ {{ __('Break') }}</strong> — {{ __('records a pause without completing the task.') }}</li>
                        <li><strong>⏹ {{ __('Finish') }}</strong> — {{ __('completes the task and allows for progress and photo reporting.') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
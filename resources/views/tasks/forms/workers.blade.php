 <div class="mb-3">
        <label class="form-label fw-bold">Персонал на об'єкті:</label><br>
        @foreach ($task->users()->wherePivot('role', '!=', 'master')->get() as $user)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="worker[]" id="worker_{{$user->id}}" checked>
                <label class="form-check-label" for="worker_{{$user->id}}">{{ $user->name }}</label>
            </div>
        @endforeach
    </div>

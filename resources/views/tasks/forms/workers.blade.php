 <div class="mb-3">
        <label class="form-label fw-bold"> {{ __('Worker List') }}:</label><br>
        @foreach ($task->users()->get() as $user)
            @if($user->pivot->role === 'worker' || $user->pivot->role === 'brigadir')
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="worker[]" id="worker_{{$user->id}}" checked>
                <label class="form-check-label" for="worker_{{$user->id}}">{{ $user->name }}
                ({{ __($user->pivot->role) }})
                </label>
            </div>
            @endif
        @endforeach
    </div>

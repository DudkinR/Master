<div class="mb-3">
        <label class="form-label fw-bold">{{ __('Equipment Availability') }}:</label><br>
        @foreach ($task->equipments as $equipment)
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="equipment_ids[]" value ="{{ $equipment->id }}" type="checkbox" id="equipment_{{$equipment->id}}" checked>
                <label class="form-check-label" for="equipment_{{$equipment->id}}">{{ $equipment->name }}</label>
            </div>
        @endforeach
    </div>
    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createEquipmentModal-{{ $task->id }}">
                ➕ {{ __('Add Equipment') }}
            </button>

            <div class="modal fade" id="createEquipmentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="equipmentModalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('equipments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="views" value="showtask">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                          
                            <div class="modal-header">
                                <h5 class="modal-title" id="equipmentModalLabel-{{ $task->id }}">{{ __('New Equipment') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Name') }}" required>
                                    <label for="name">{{ __('Name') }}</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="{{ __('Description') }}" style="height: 80px;" required></textarea>
                                    <label for="description">{{ __('Description') }}</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="{{ __('Quantity') }}" value="1" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}" required  value="{{ __('pcs') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100">💾 Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
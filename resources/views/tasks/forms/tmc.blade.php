 <div class="mb-3">
        <label class="form-label fw-bold">{{ __('Availability of materials') }}</label><br>
            
        @foreach ($task->materials as $material)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="material_{{$material->id}}" checked>
                <label class="form-check-label" for="material_{{$material->id}}">{{ $material->name }}</label>
            </div>
        @endforeach
    </div>
     <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#createMaterialModal-{{ $task->id }}">
            ➕  {{ __('Add material') }}
                         
        </button>
            <!-- Модальне вікно -->
            <div class="modal fade" id="createMaterialModal-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('materials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="views" value="showtask">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $task->id }}">
                                    {{ __('New material') }}</h5>
                                    
                                <button type="button" class="btn-close" data-bs-dismiss="modal"   title="{{ __('Close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Title') }}" required>
                                    <label for="name">
                                        {{ __('Title') }}</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <textarea class="form-control" id="description" name="description" placeholder="{{__('Description') }}"  style="height: 80px" required></textarea>
                                    <label for="description">Опис</label>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="quantity" placeholder="Кількість" value="0" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="unit" placeholder="Одиниця" required  value="шт">
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
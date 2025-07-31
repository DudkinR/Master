@extends('layouts.app')
@section('title', 'Завдання')
@section('content')

<div class="container my-5">

    {{-- Сповіщення --}}
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif
    {{-- Форма створення нового завдання --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Створити нове завдання</h5>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                {{-- hidden rout back --}}
                <input type="hidden" name="back_route" value="{{ route('tasks.index') }}">

                {{-- Основні поля завдання --}}
                <div class="row g-3 mb-4">
                    {{-- Проект --}}
                    <div class="col-md-12">
                        <label for="project_id" class="form-label">Проєкт</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option disabled selected>Оберіть проєкт</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    
                    {{-- Порядок і Назва --}}
                    <div class="col-md-6">
                        <label for="order" class="form-label">Порядок</label>
                        <input type="number" class="form-control" id="order" name="order" 
                               value="{{ $projects->first()?->tasks->count() + 1 ?? 1 }}">
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Назва</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    {{-- Опис --}}
                    <div class="col-12">
                        <label for="description" class="form-label">Опис</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    {{-- Дати --}}
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Початок</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Завершення</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ now()->addDays(30)->format('Y-m-d') }}">
                    </div>

                    {{-- Години та прогрес --}}
                    <div class="col-md-4">
                        <label for="estimated_hours" class="form-label">Оцінка годин</label>
                        <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" step="0.01" value="0">
                    </div>

                    <div class="col-md-4">
                        <label for="actual_hours" class="form-label">Факт годин</label>
                        <input type="number" class="form-control" id="actual_hours" name="actual_hours" step="0.01" value="0">
                    </div>

                    <div class="col-md-4">
                        <label for="progress" class="form-label">Прогрес (%)</label>
                        <input type="number" class="form-control" id="progress" name="progress" max="100" value="0">
                    </div>

                    {{-- Кількість і одиниця --}}
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Кількість</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" step="1" value="0">
                    </div>

                    <div class="col-md-6">
                        <label for="unit" class="form-label">Одиниця</label>
                        <select class="form-select" id="unit" name="unit">
                            <option disabled selected>Оберіть одиницю</option>
                            @foreach(['шт' => 'Штуки', 'м' => 'Метри', 'м2' => 'м²', 'м3' => 'м³', 'год' => 'Години', 'т' => 'Тони', 'л' => 'Літри', 'кг' => 'Кілограми', 'шт/год' => 'Шт/год'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

              
                </div>

                {{-- === Матеріали === --}}
                <h4>Матеріали</h4>
                <div id="materials-wrapper" class="mb-4">
                    <div class="row g-2 align-items-end material-row">
                        <div class="col-md-5">
                            <label class="form-label">Назва</label>
                            <input type="text" name="materials[0][name]" class="form-control" placeholder="Назва матеріалу">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Опис</label>
                            <input type="text" name="materials[0][description]" class="form-control" placeholder="Опис">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Кількість</label>
                            <input type="number" name="materials[0][quantity]" class="form-control" value="1" min="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Одиниця</label>
                            <input type="text" name="materials[0][unit]" class="form-control" placeholder=""  value="шт">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addMaterial()">➕ Додати матеріал</button>

                {{-- === Обладнання === --}}
                <h4>Обладнання</h4>
                <div id="equipments-wrapper" class="mb-4">
                    <div class="row g-2 align-items-end equipment-row">
                        <div class="col-md-5">
                            <label class="form-label">Назва</label>
                            <input type="text" name="equipments[0][name]" class="form-control" placeholder="Назва обладнання">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Опис</label>
                            <input type="text" name="equipments[0][description]" class="form-control" placeholder="Опис">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Кількість</label>
                            <input type="number" name="equipments[0][quantity]" class="form-control" value="1" min="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Одиниця</label>
                            <input type="text" name="equipments[0][unit]" class="form-control" placeholder=""  value="шт">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addEquipment()">➕ Додати обладнання</button>

                {{-- === Техніка === --}}
                <h4>Техніка</h4>
                <div id="machines-wrapper" class="mb-4">
                    <div class="row g-2 align-items-end machine-row">
                        <div class="col-md-5">
                            <label class="form-label">Назва</label>
                            <input type="text" name="machines[0][name]" class="form-control" placeholder="Назва техніки">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Опис</label>
                            <input type="text" name="machines[0][description]" class="form-control" placeholder="Опис">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Кількість</label>
                            <input type="number" name="machines[0][quantity]" class="form-control" value="1" min="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Одиниця</label>
                            <input type="text" name="machines[0][unit]" class="form-control" placeholder=""  value="шт">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addMachine()">➕ Додати техніку</button>

                {{-- Кнопка збереження --}}
                <button type="submit" class="btn btn-primary w-100" id="submit_button">Створити завдання з ресурсами</button>
            </form>
        </div>
    </div>
</div>

{{-- Скрипти для додавання нових полів --}}
<script>
    let materialIndex = 1;
    function addMaterial() {
        const wrapper = document.getElementById('materials-wrapper');
        const newRow = document.querySelector('.material-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/\d+/, materialIndex);
            input.setAttribute('name', newName);
            input.value = '';
            if(input.type === 'text') input.value = '';
            else if(input.type === 'number') input.value = 1;
            else if(input.type === 'date') input.value = '';
            else if(input.type === 'select-one') input.value = 'шт';        
        });
        wrapper.appendChild(newRow);
        materialIndex++;
    }

    let equipmentIndex = 1;
    function addEquipment() {
        const wrapper = document.getElementById('equipments-wrapper');
        const newRow = document.querySelector('.equipment-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/\d+/, equipmentIndex);
            input.setAttribute('name', newName);
            input.value = '';
            if(input.type === 'text') input.value = '';
            else if(input.type === 'number') input.value = 1;
            else if(input.type === 'date') input.value = '';
            else if(input.type === 'select-one') input.value = 'шт';
        });
        wrapper.appendChild(newRow);
        equipmentIndex++;
    }

    let machineIndex = 1;
    function addMachine() {
        const wrapper = document.getElementById('machines-wrapper');
        const newRow = document.querySelector('.machine-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/\d+/, machineIndex);
            input.setAttribute('name', newName);
            input.value = '';
            if(input.type === 'text') input.value = '';
            else if(input.type === 'number') input.value = 1;
            else if(input.type === 'date') input.value = '';
            else if(input.type === 'select-one') input.value = 'шт';
        });
        wrapper.appendChild(newRow);
        machineIndex++;
    }
</script>

@endsection

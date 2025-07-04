@extends('layouts.app')
@section('title', 'Список користувачів')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Список користувачів</h2>
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <!-- Фільтри користувачів -->
    <div class="card mb-3">
    <div class="card-header">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#userFilters">
        🔍 Фільтри користувачів
        </button>
    </div>
    <div id="userFilters" class="collapse">
        <div class="card-body">
        <form  action="{{ route('users.index') }}" method="POST" class="mb-3">
            @csrf
           
            <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Пошук за ім'ям або email" value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Пошук</button>
            </div>
            <div class="accordion" id="filterAccordion">
            <!-- Ролі -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRoles">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoles">
                    Ролі
                </button>
                </h2>
                <div id="collapseRoles" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
                <div class="accordion-body">
                    @foreach($roles as $role)
                        @if(Auth::user()->roles->first()->name !== 'Admin' && $role->name === 'Admin')
                            @continue
                        @endif
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox"  name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                {{ in_array($role->id, request('roles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">{{ __($role->name) }}</label>
                            </div>
                    @endforeach
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="no_role" id="no_role" {{ request('no_role') ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_role">Без ролі</label>
                    </div>
                </div>
                </div>
            </div>
            <!-- Підприємства -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEnterprises">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnterprises">
                    Підприємства
                </button>
                </h2>
                <div id="collapseEnterprises" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
                <div class="accordion-body">
                    @foreach($enterprises as $enterprise)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox"  name="enterprises[]" value="{{ $enterprise->id }}" id="enterprise_{{ $enterprise->id }}"
                        {{ in_array($enterprise->id, request('enterprises', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enterprise_{{ $enterprise->id }}">{{ $enterprise->name }}</label>
                    </div>
                    @endforeach
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="no_enterprise" id="no_enterprise" {{ request('no_enterprise') ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_enterprise">Без підприємства</label>
                    </div>
                </div>
                </div>
            </div>
            <!-- Підрозділи -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDepartments">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDepartments">
                    Підрозділи
                </button>
                </h2>
                <div id="collapseDepartments" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
                <div class="accordion-body">
                    @foreach($departments as $department)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox"  name="departments[]" value="{{ $department->id }}" id="department_{{ $department->id }}"
                        {{ in_array($department->id, request('departments', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="department_{{ $department->id }}">{{ $department->name }}</label>
                    </div>
                    @endforeach
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox"  name="no_department" id="no_department" {{ request('no_department') ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_department">Без підрозділу</label>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
            <button type="submit" class="btn btn-success">Застосувати фільтри</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Скинути фільтри</a>
            </div>
        </form>
        </div>
    </div>
    </div>
    <!-- Кінець фільтрів -->
    @if($users->isEmpty())
      <div class="alert alert-info">Немає користувачів для відображення.</div>
    @endif
    <div class="table-responsive">    
        <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Ім'я</th>
            <th scope="col">Електронна пошта</th>
            <th scope="col">Position </th>
            <th scope="col">Роль</th>
            <th scope="col">Entrprise</th>
            <th scope="col">Department</th>            
            <th scope="col">Дії</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>    
                <td>{{ $user->position ?? '-' }}</td>         
                <td @if($user->status === 'sick') class="text-warning" @elseif($user->status === 'vacation') class="text-success" @endif>
                    @if($user->roles->isEmpty())
                    <span class="text-muted">Без ролі</span>
                    @else
                    {{ $user->roles-> first()->name }}
                    @endif
                </td> 
                
                <td>{{ $user->enterprises->first()->name ?? '-' }}</td>
                <td>{{ $user->departments->first()->name ?? '-' }}</td>
                <td>
                    @if((Auth::user()->roles->first()->name !== 'Admin' && isset($user->roles->first()->name)&& $user->roles->first()->name == 'Admin') || Auth::user()->id == $user->id)
                    <span class="text-muted">Редагування та видалення недоступні</span>                   
                    @else
                     <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-phone="{{ $user->phone }}"
                        data-position="{{ $user->position }}"
                        data-status="{{ $user->status }}"
                        data-roles='@json($user->roles->pluck("id"))'
                        data-enterprises='@json($user->enterprises->pluck("id"))'
                        data-departments='@json($user->departments->pluck("id"))'>
                        Редагувати
                    </button>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                    </form>
                    @endif
                </td>
                </tr>
                @if(Auth::user()->roles->first()->name !== 'Admin')
                    @foreach($user->children as $child)
                        <tr>
                            <td>{{ $child->id }}</td>
                            <td>{{ $child->name }}</td>
                            <td>{{ $child->email }}</td>
                            <td>{{ $child->position ?? '-' }}</td>
                            <td @if($child->status === 'sick') class="text-warning" @elseif($child->status === 'vacation') class="text-success" @endif>
                                @if($child->roles->isEmpty())
                                <span class="text-muted">Без ролі</span>
                                @else
                                {{ $child->roles->first()->name }}
                                @endif
                            </td>
                            <td>{{ $child->enterprises->first()->name ?? '-' }}</td>
                            <td>{{ $child->departments->first()->name ?? '-' }}</td>
                            <td>
                            @if((Auth::user()->roles->first()->name !== 'Admin' && isset($child->roles->first()->name) && $child->roles->first()->name == 'Admin') || Auth::user()->id == $child->id)
                            <span class="text-muted">Редагування та видалення недоступні</span>
                            @else
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                data-id="{{ $child->id }}"
                                data-name="{{ $child->name }}"
                                data-email="{{ $child->email }}"
                                data-phone="{{ $child->phone }}"
                                data-position="{{ $child->position }}"
                                data-status="{{ $child->status }}"
                                data-roles='@json($child->roles->pluck("id"))'
                                data-enterprises='@json($child->enterprises->pluck("id"))'
                                data-departments='@json($child->departments->pluck("id"))'>
                                Редагувати
                            </button>
                            <form action="{{ route('users.destroy', $child->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                            </form>
                            @endif
                            </td>
                        </tr>                       
                 
                        @if($child->children->isNotEmpty())
                            @foreach($child->children as $grandChild)
                                <tr>
                                    <td>{{ $grandChild->id }}</td>
                                    <td>{{ $grandChild->name }}</td>
                                    <td>{{ $grandChild->email }}</td>
                                    <td>{{ $grandChild->position ?? '-' }}</td>
                                    <td @if($grandChild->status === 'sick') class="text-warning" @elseif($grandChild->status === 'vacation') class="text-success" @endif    >
                                        @if($grandChild->roles->isEmpty())
                                        <span class="text-muted">Без ролі</span>
                                        @else                        
                                        {{ $grandChild->roles->first()->name }} 
                                        @endif
                                    </td>
                                    <td>{{ $grandChild->enterprises->first()->name ?? '-' }}</td>
                                    <td>{{ $grandChild->departments->first()->name ?? '-' }}</td>
                                    <td>
                                        @if((Auth::user()->roles->first()->name !== 'Admin' && isset($grandChild->roles->first()->name) && $grandChild->roles->first()->name == 'Admin') || Auth::user()->id == $grandChild->id)
                                        <span class="text-muted">Редагування та видалення недоступні</span>
                                        @else
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                            data-id="{{ $grandChild->id }}"
                                            data-name="{{ $grandChild->name }}"
                                            data-email="{{ $grandChild->email }}"
                                            data-phone="{{ $grandChild->phone }}"
                                            data-position="{{ $grandChild->position }}"
                                            data-status="{{ $grandChild->status }}"
                                            data-boss="{{ optional($user->supervisors->first())->id }}"
                                            data-subordinates='@json($user->children->pluck("id"))'
                                            data-roles='@json($grandChild->roles->pluck("id"))'
                                            data-enterprises='@json($grandChild->enterprises->pluck("id"))'
                                            data-departments='@json($grandChild->departments->pluck("id"))'>
                                            Редагувати
                                        </button>
                                        <form action="{{ route('users.destroy', $grandChild->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                                        </form> 
                                        @endif    
                                    </td>
                                </tr>
                            @endforeach   
                        @endif             
                    @endforeach
                @endif              
            @endforeach
        </tbody>
        </table>    
        <div class="mt-3">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Додати нового користувача</a>
        </div>
    </div>
</div>  
<!-- modal вікно для редгування користувача -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Редагувати користувача</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Форма редагування користувача -->
                <form id="editUserForm"  action="{{ route('users.update', 0) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="userId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ім'я</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Електронна пошта</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Залиште порожнім, якщо не хочете змінювати пароль.</small>
                    </div>
                   <select class="form-select" id="role" name="role">
                        @foreach($roles as $role)
                            @php
                                $currentRole = Auth::user()->roles->first()->name;
                                $allowed = match ($currentRole) {
                                    'Admin' => true,
                                    'Boss' => in_array($role->name, ['Boss', 'Master', 'Brigadir', 'Worker']),
                                    'Master' => in_array($role->name, ['Master', 'Brigadir', 'Worker']),
                                    'Brigadir' => in_array($role->name, ['Brigadir', 'Worker']),
                                    'Worker' => $role->name === 'Worker',
                                    default => false,
                                };
                            @endphp

                            @if ($allowed && ($currentRole !== 'Admin' || $role->name !== 'Admin'))
                                <option value="{{ $role->id }}">{{ __($role->name) }}</option>
                            @endif
                        @endforeach
                    </select>

                    <div class="mb-3">
                        <label for="enterprises" class="form-label">Підприємства</label>
                        <select class="form-select" id="enterprises" name="enterprise" >
                            @foreach($enterprises as $enterprise)
                                <option value="{{ $enterprise->id }}">{{ $enterprise->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="departments" class="form-label">Підрозділи</label>
                        <select class="form-select" id="departments" name="department" >
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Посада</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="Посада користувача">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Статус</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Активний</option>
                            <option value="sick">На лікарняному</option>
                            <option value="vacation">У відпустці</option>
                            </select>
                    </div>
                    <!-- Додайте інші поля за потребою -->
                     {{-- Хто начальник --}}
                    <div class="mb-3">
                        <label for="boss" class="form-label">Начальник</label>
                        <select class="form-select" id="boss" name="boss">
                            <option value="">Не вказано</option>
                            @foreach($users as $user)
                                @if(Auth::user()->id == $user->id  || Auth::user()->roles->first()->name === 'Admin' && $user->roles->first()->name === 'Worker')
                                    @continue
                                @endif
                                <option value="{{ $user->id }}">{{$user->roles->first()->name}} {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                    {{-- Хто підлеглий [] --}}
                    <div class="mb-3">
                        <label for="subordinates" class="form-label">Підлеглі</label>
                        <select class="form-select" id="subordinates" row=10 name="subordinates[]" multiple>
                            @foreach($users as $user)
                                @if(Auth::user()->id == $user->id || Auth::user()->roles->first()->name === 'Admin' && $user->roles->first()->name === 'Boss')
                                    @continue
                                @endif
                                <option value="{{ $user->id }}">{{$user->roles->first()->name}} {{ $user->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Виберіть підлеглих користувача. Ви можете вибрати декілька користувачів.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('editUserModal');

    modalEl.addEventListener('shown.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        // Отримання даних з атрибутів
        const id = button.dataset.id;
        const name = button.dataset.name;
        const email = button.dataset.email;
        const phone = button.dataset.phone;
        const position = button.dataset.position;
        const status = button.dataset.status;
        // Супер JSON-атрибути
        const boss         = button.dataset.boss; // рядок з одним ID або ""
        const subordinates = JSON.parse(button.dataset.subordinates || '[]');
        const roles        = JSON.parse(button.dataset.roles        || '[]');
        const enterprises  = JSON.parse(button.dataset.enterprises  || '[]');
        const departments  = JSON.parse(button.dataset.departments  || '[]');

 
      // Універсальна функція для мульти-селектів
        const setMultiSelect = (selectId, values) => {
            const select = modalEl.querySelector(`#${selectId}`);
            if (!select) return;
            [...select.options].forEach(opt => {
                opt.selected = values.includes(parseInt(opt.value));
            });
        };

        // Установлюємо ролі, підприємства, підрозділи
        setMultiSelect('roles',       roles);
        setMultiSelect('enterprises', enterprises);
        setMultiSelect('departments', departments);

        // Встановлюємо начальника (single select)
        const bossSelect = modalEl.querySelector('#boss');
        if (bossSelect) bossSelect.value = boss || '';

        // Встановлюємо підлеглих (multiple select)
        setMultiSelect('subordinates', subordinates);
    });
});
</script>
@endsection

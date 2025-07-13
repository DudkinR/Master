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
        🔍 Фільтри користувачів
       <input type="text" id="searchInput" class="form-control mb-3" placeholder="Пошук за ім'ям або email">
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
        <tbody id="userTableBody">
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
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                   
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
                              <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                
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
                                          <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Переглянути</a>
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
      

        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>  
        <div class="mt-3">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Додати нового користувача</a>
        </div>
    </div>
</div>  
<script>

     @php
    $icons = [
                'Admin' => '👑👑👑',
                'Boss' => '👑',
                'Engineer' => '👨‍🔧',
                'Master' => '👷',
                'Brigadir' => '🛠️',
                'Worker' => '🔧',
                'Unassigned' => '🔍',
            ];
    @endphp
    const icons = @json($icons);
    const usersRaw = @json($users->values());
   
    const users = usersRaw.map(user => ({
        id: user.id,
        name: user.name,
        email: user.email,
        position: user.position ?? '-',
        role: user.roles.length ? user.roles[0].name : 'Без ролі',
        enterprise: user.enterprises.length ? user.enterprises[0].name : '-',
        department: user.departments.length ? user.departments[0].name : '-',
        status: user.status
    }));
    console.log(users);
    const userTableBody = document.getElementById('userTableBody');
    const searchInput = document.getElementById('searchInput');
    const pagination = document.getElementById('pagination');

    let currentPage = 1;
    const rowsPerPage = 10;

    function displayList(items, wrapper, rowsPerPage, page) {
        wrapper.innerHTML = '';
        page--;

        const start = page * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedItems = items.slice(start, end);
        
        for (let user of paginatedItems) {
            const row = document.createElement('tr');
            @if(Auth::user()->roles->first()->name == 'Admin' ||Auth::user()->roles->first()->name == 'Boss' ||Auth::user()->roles->first()->name == 'Master' )
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td><a href="/users/${user.id}" >${user.name}</a></td>
                    <td>${user.email}</td>
                    <td>${user.position}</td>
                    <td class="${user.status === 'sick' ? 'text-warning' : user.status === 'vacation' ? 'text-success' : ''}">
                    ${icons[user.role] || ''}
                    ${user.role}
                    </td>
                    <td>${user.enterprise}</td>
                    <td>${user.department}</td>
                    <td> 
                    <a href="/users/${user.id}" class="btn btn-info btn-sm">{{__('View')}}</a>
                    </td>
                `;   
            @else
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.position}</td>
                    <td class="${user.status === 'sick' ? 'text-warning' : user.status === 'vacation' ? 'text-success' : ''}">${icons[user.role] || ''}${user.role}</td>
                    <td>${user.enterprise}</td>
                    <td>${user.department}</td>
                    <td>
                        -
                    </td>
                `;
            @endif
            wrapper.appendChild(row);
        }
     
    }

    function setupPagination(items, wrapper, rowsPerPage) {
        wrapper.innerHTML = '';

        const pageCount = Math.ceil(items.length / rowsPerPage);
        for (let i = 1; i <= pageCount; i++) {
            let btn = paginationButton(i, items);
            wrapper.appendChild(btn);
        }
    }

    function paginationButton(page, items) {
        const button = document.createElement('li');
        button.className = 'page-item' + (currentPage === page ? ' active' : '');
        button.innerHTML = `<a class="page-link" href="#">${page}</a>`;

        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentPage = page;
            displayList(items, userTableBody, rowsPerPage, currentPage);

            let currentBtn = document.querySelector('.pagination li.active');
            if (currentBtn) currentBtn.classList.remove('active');
            button.classList.add('active');
        });

        return button;
    }

    function applySearch() {
        const query = searchInput.value.toLowerCase();
        const filtered = users.filter(user =>
            user.name.toLowerCase().includes(query) ||
            user.email.toLowerCase().includes(query) ||
            user.position.toLowerCase().includes(query) ||
            user.role.toLowerCase().includes(query) ||
            user.enterprise.toLowerCase().includes(query) ||
            user.department.toLowerCase().includes(query)
        );

        currentPage = 1;
        console.log(filtered);
        displayList(filtered, userTableBody, rowsPerPage, currentPage);
        setupPagination(filtered, pagination, rowsPerPage);
    }

    // ініціалізація
    displayList(users, userTableBody, rowsPerPage, currentPage);
    setupPagination(users, pagination, rowsPerPage);

    searchInput.addEventListener('input', applySearch);
</script>

@endsection

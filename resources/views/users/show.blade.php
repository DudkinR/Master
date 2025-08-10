
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

    <div class="container">
        {{--back to index--}}
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Повернутися до списку користувачів</a>
            </div>
        </div>
        <!-- Форма редагування користувача -->
                <form id="editUserForm"  action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="user" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ім'я</label>
                        <input type="text" class="form-control" id="name" name="name" value ="{{ $user->name }}" required>
                    </div>                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" placeholder="Телефон користувача" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Електронна пошта</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{ $user->email }}" placeholder="Електронна пошта користувача">
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
                                <option value="{{ $role->id }}" {{$user->roles->first()->id === $role->id ? 'selected' : ''}}>
                                    {{ $icons[$role->name] ?? '' }}                                      
                                    {{ __($role->name) }}
                                </option>
                            @endif
                        @endforeach
                    </select>                            
                    <div class="mb-3">
                        <label for="position" class="form-label">Посада</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="Посада користувача" value="{{ $user->position }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Статус</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" @if($user->status === 'active') selected @endif>Активний</option>                            
                            <option value="sick" @if($user->status === 'sick') selected @endif>На лікарняному</option>
                            <option value="vacation" @if($user->status === 'vacation') selected @endif>У відпустці</option>
                            </select>
                    </div>
                    <!-- Додайте інші поля за потребою -->
                     {{-- Хто начальник --}}
                    <div class="mb-3">
                        <label for="boss" class="form-label">Начальник</label>
                        <select class="form-select" id="boss" name="boss">
                            <option value="">Не вказано</option>
                            @foreach($users as $usr)                                
                                <option value="{{ $usr->id }}"
                                @if(isset($user->supervisors->first()->id) && $user->supervisors->first()->id ==$usr->id)
                                  selected 
                                  @endif
                                  >                                
                                    {{ $icons[$usr->roles->first()->name] ?? '' }} {{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                    {{-- Хто підлеглий [] --}}
                    <div class="mb-3">
                        <label for="subordinates" class="form-label">Підлеглі</label>
                        <select class="form-select" id="subordinates" row=10 name="subordinates[]" multiple>
                        <option value="">Не вказано</option>
                            {{-- Виводимо всіх користувачів, окрім поточного та адміністраторів --}}    
                            @foreach($users as $сhild)

                                <option value="{{ $сhild->id }}" @if($children->contains($сhild->id)) selected @endif >{{ $icons[$сhild->roles->first()->name] ?? '' }}  {{ $сhild->name }}</option>
                                   
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Виберіть підлеглих користувача. Ви можете вибрати декілька користувачів.</small>
                    </div>
                    @if(Auth::user()->hasRole('Admin'))
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Новий пароль (залиште порожнім, якщо не хочете змінювати)">
                    </div>
                    {{-- Enterprise select --}}
                        @php 
                            $enterprises = \App\Models\Enterprise::all();
                        @endphp
                    <div class="mb-3">
                        <label for="enterprise" class="form-label">Підприємство</label>
                        <select class="form-select" id="enterprise" name="enterprise">
                            <option value="">Не вказано</option>
                            @foreach($enterprises as $enterprise)
                                <option value="{{ $enterprise->id }}" @if($user->enterprises[0]->id === $enterprise->id) selected @endif>{{ $enterprise->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('enterprises.create') }}" class="btn btn-success">Додати нове підприємство</a>
                    </div>
                    {{-- Department  select --}}
                    <div class="mb-3">
                        <label for="department" class="form-label">Відділ</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">Не вказано</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if($user->departments[0]->id === $department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('departments.create') }}" class="btn btn-success">Додати новий відділ</a>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                </form>
    </div>
</div>
@endsection
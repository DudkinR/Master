
@extends('layouts.app')
@section('title', 'User Details')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">User Details   </h2>
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
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
               
                    <a href="{{ route('impersonate', $user->id) }}" class="btn btn-sm btn-primary">
                        impersonate {{ $user->name }}
                    </a>
             
            </div>
        </div>
        <!-- Форма редагування користувача -->
                <form id="editUserForm"  action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="user" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value ="{{ $user->name }}" required>
                    </div>                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" placeholder="Phone number" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"> Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{ $user->email }}" placeholder="Email address">
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
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="User position" value="{{ $user->position }}" >
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" @if($user->status === 'active') selected @endif>Active</option>                            
                            <option value="sick" @if($user->status === 'sick') selected @endif>Sick</option>
                            <option value="vacation" @if($user->status === 'vacation') selected @endif>Vacation</option>
                            </select>
                    </div>
                    <!-- Додайте інші поля за потребою -->
                     {{-- Хто начальник --}}
                    <div class="mb-3">
                        <label for="boss" class="form-label">Boss</label>
                        <select class="form-select" id="boss" name="boss">
                            <option value="">Not specified</option>
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
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            Already selected subordinates
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                                @foreach($users as $сhild)
                                    @if($children->contains($сhild->id))
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="subordinates[]"
                                                    value="{{ $сhild->id }}"
                                                    id="subordinate_{{ $сhild->id }}"
                                                    checked>
                                                <label class="form-check-label" for="subordinate_{{ $сhild->id }}">
                                                    {{ $icons[$сhild->roles->first()->name] ?? '' }} {{ $сhild->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Мультиселект для нових підлеглих --}}
                    <div class="mb-2">
                        <strong>Додати нових:</strong>
                        <select class="form-select" id="subordinates" name="subordinates[]" multiple size="8">
                            @foreach($users as $сhild)
                                @if(!$children->contains($сhild->id))
                                    <option value="{{ $сhild->id }}">
                                        {{ $icons[$сhild->roles->first()->name] ?? '' }} {{ $сhild->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <small class="form-text text-muted">Select subordinates for the user. You can select multiple users.</small>
                </div>

                    @if(Auth::user()->hasRole('Admin'))
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="New password (leave blank if you don't want to change it)">
                    </div>
                    {{-- Enterprise select --}}
                        @php 
                            $enterprises = \App\Models\Enterprise::all();
                        @endphp
                    <div class="mb-3">
                        <label for="enterprise" class="form-label">Enterprise</label>
                        <select class="form-select" id="enterprise" name="enterprise">
                            <option value="">Not specified</option>
                            @foreach($enterprises as $enterprise)
                                <option value="{{ $enterprise->id }}" @if($user->enterprises[0]->id === $enterprise->id) selected @endif>{{ $enterprise->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('enterprises.create') }}" class="btn btn-success">Add New Enterprise</a>
                    </div>
                    {{-- Department  select --}}
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">Not specified</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if($user->departments[0]->id === $department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('departments.create') }}" class="btn btn-success">Add New Department</a>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
    </div>
</div>
@endsection
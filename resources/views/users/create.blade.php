@extends('layouts.app')
@section('title', 'Create User')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('New user') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.create') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="enterprise" class="col-md-4 col-form-label text-md-end">{{ __('Enterprise') }}</label>
                            <div class="col-md-6">
                                <select name="enterprise" id="enterprise">
                                    <option value="" disabled {{ old('enterprise') ? '' : 'selected' }}></option>
                                    @foreach($enterprises as $enterprise)
                                        <option value="{{ $enterprise->id }}"
                                            @if(old('enterprise') == $enterprise->id) selected @endif>
                                            {{ $enterprise->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">{{ __('Enter the name of your enterprise.') }}</small>
                                @error('enterprise')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                         <div class="row mb-3">
                            <label for="department" class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>
                            <div class="col-md-6">
                                <select name="department" id="department">
                                    <option value="" disabled {{ old('department') ? '' : 'selected' }}></option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            @if(old('department') == $department->id) selected @endif>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">{{ __('Enter the name of your department.') }}</small>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>
                            <div class="col-md-6">
                                <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" >
                                   <option value="" disabled {{ old('role') ? '' : '' }}>Select a role</option>
                                        @foreach($roles as $role)
                                            @if(!Auth::user()->roles->contains('name', 'Admin') && $role->name === 'Admin')
                                                @continue
                                            @endif
                                            <option value="{{ $role->id }}"
                                                @if(old('role'))
                                                    {{ old('role') == $role->id ? 'selected' : '' }}
                                                @elseif($role->name === 'Worker')
                                                    selected
                                                @endif
                                            >
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="position" class="col-md-4 col-form-label text-md-end">{{ __('Position') }}</label>  
                            <div class="col-md-6">
                                <input id="position" type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position') }}"  autocomplete="position">
                                <small class="form-text text-muted">Enter your position.</small>
                                @error('position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select id="status" class="form-select @error('status') is-invalid @enderror" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="sick" {{ old('status') == 'sick' ? 'selected' : '' }}>{{ __('Sick') }}</option>
                                    <option value="vacation" {{ old('status') == 'vacation' ? 'selected' : '' }}>{{ __('Vacation') }}</option>
                                </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>

                         <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}"  autocomplete="phone">
                                <small class="form-text text-muted">Enter your phone number.</small>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

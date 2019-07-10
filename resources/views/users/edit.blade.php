@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            @component('components.errors')
            failed to update the user for the following reasons
            @endcomponent
            @component('components.success')
            user account has been updated!
            @endcomponent
        </div>
    </div>
    <div class="row justify-content-center">
        <form class="col-md-8" method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @method('patch')
            @csrf
            <div class="card mb-4">
                <div class="card-header"><i class="fa fa-edit" aria-hidden="true"></i> Edit User Account</div>
                <div class="card-body">
                    <h5 class="card-title text-center">User Settings</h5>
                    {{-- Name input --}}
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" name="name" type="text" class="form-control"
                                value="@if(old('name')){{ old('name') }}@else{{ $user->name }}@endif"
                                @cannot('updateElevate', $user){{ __('disabled') }}@endcannot>
                        </div>
                    </div>

                    {{-- Email input --}}
                    <div class="form-group row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        <div class="col-md-6">
                            <input id="email" name="email" type="email" class="form-control"
                                value="@if(old('name')){{ old('email') }}@else{{ $user->email }}@endif"
                                @cannot('updateElevate', $user){{__('disabled')}}@endcannot>
                        </div>
                    </div>

                    @can('authIsUser', $user)
                    {{-- Link to password reset page (assumed that the currently logged in user owns the account) --}}
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-2">
                            <a class="btn btn-primary btn-block" href="{{route('password.change.request')}}"
                                role="button">Change Password</a>
                        </div>
                    </div>
                    @endcan

                    {{-- Password input --}}
                    @can('updateElevate', $user)
                    <div class="form-group row">
                        <label for="password"
                            class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                autocomplete="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Password confirm input --}}
                    <div class="form-group row">
                        <label for="password_confirmation"
                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm New Password') }}</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control"
                                name="password_confirmation" autocomplete="password">
                        </div>
                    </div>
                    @endcan
                    <hr>

                    <h5 class="card-title text-center">Profile</h5>
                    {{-- First name --}}
                    <div class="form-group row">
                        <label for="first_name"
                            class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="first_name" id="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="@if(old('first_name')){{ old('first_name') }}@else{{$user->profile->first_name}}@endif"
                                required autocomplete="name">
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Family name --}}
                    <div class="form-group row">
                        <label for="family_name"
                            class="col-md-4 col-form-label text-md-right">{{ __('Family Name') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="family_name" id="family_name"
                                class="form-control @error('family_name')is-invalid @enderror"
                                value="@if(old('family_name')){{ old('family_name') }}@else{{$user->profile->family_name}}@endif"
                                required autocomplete="name">

                            @error('family_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Avatar --}}
                    <div class="form-group row">
                        <div class="input-group col-md-8 mx-auto">
                            <div class="custom-file">
                                <input type="file" class="form-control-file @error('avatar') is-invalid @enderror"
                                    name="avatar" id="avatar" aria-describedby="avatarHelpId"
                                    accept=".jpeg,.png,.jpg,.gif,.svg">
                                @error('avatar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <small id="avatarHelpId" class="form-text text-muted">
                                    A picture that represents your account. Only accepts pictures below the size of 2MB.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="form-group row">
                        <label for="social"
                            class="col-md-4 col-form-label text-md-right">{{ __('Social Media Link') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="social" id="social"
                                class="form-control @error('social')is-invalid @enderror"
                                value="@if(old('social')){{ old('social') }}@else{{ $user->profile->social }}@endif"
                                autocomplete="url">
                            @error('social')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group-row mb-3">
                        <div class="col-md-8 mx-auto">
                            <button type="submit" class="btn btn-primary btn-block">Update Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Admin Panel --}}
    @can('updateElevate', $user)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-terminal" aria-hidden="true"></i>
                    {{ __('Administrator Panel') }}</div>
                <div class="card-body">
                    @can('assignRole', $user)
                    <form action="{{ route('users.role.assign', $user) }}" method="post" id="assign_role">
                        @method('patch')
                        @csrf
                        {{-- Assign Role --}}
                        <div class="form-group row">
                            <label for="role" class="form-label col-md-4 col-form-label text-md-right">Role</label>
                            <div class="col-md-6">
                                <select name="role" id="role" class="custom-select">
                                    <option value="admin" @if($user->hasRole('admin')) selected @endif>Admin</option>
                                    <option value="user-admin" @if($user->hasRole('user-admin')) selected @endif>User
                                        Admin</option>
                                    <option value="user" @if($user->hasRole('user')) selected @endif>User</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Assign Role</button>
                            </div>
                        </div>
                    </form>
                    @endcan

                    @can('suspend', $user)
                    <form action="{{ route('users.suspend', $user) }}" method="post">
                        @method("DELETE")
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                @if(!$user->hasRole('suspended'))
                                <button type="submit" class="btn btn-danger btn-block">Suspend User</button>
                                @else
                                <button type="submit" class="btn btn-success btn-block">Remove Suspension</button>
                                @endif
                            </div>
                        </div>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
        {{-- End Admin Panel --}}

    </div>
</div>
@endsection

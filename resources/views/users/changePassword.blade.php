@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.edit', auth()->user()) }}">Edit User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Change Password</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            @component('components.errors')
            Failed to change the password due to the following reasons
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col">
            @component('components.success')
            Successfully changed the password
            @endcomponent
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fa fa-edit" aria-hidden="true"></i>{{ __(' Change Password')}}</div>
                <form class="card-body" method="POST" action="{{ route('password.change') }}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                        <label for="old_password"
                            class="col-md-4 col-form-label text-md-right">{{ __('Current Password') }}</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror" aria-label="old_password" id="old_password"
                                name="old_password" minlength="8" required>
                            @error('old_password')
                            <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"
                            for="password">{{ __('New Password') }}</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password"
                                aria-label="password" minlength="8" required>
                            @error('password')
                            <span class="invalid-feedback"><strong>{{$message}}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"
                            for="password_confirmation">{{ __('Confirm New Password') }}</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" aria-label="password" minlength="8" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-2">
                            <button class="btn btn-primary btn-block" type="submit">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create User Account</li>
    </ol>
</nav>
@endsection

@php
$user = auth()->user();
@endphp

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            @component('components.errors')
            Could not update your account for the following reasons.
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <form class="col-md-8" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-user-plus"></i> Create User Account</div>
                <div class="card-body">
                    <h5 class="card-title text-center">User Settings</h5>
                    {{-- Name input disabled --}}
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" value="{{ old('name') }}">
                        </div>
                    </div>

                    {{-- Email input disabled --}}
                    <div class="form-group row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>

                    {{-- Password input --}}
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                autocomplete="password">
                        </div>
                    </div>

                    {{-- Password confirm input --}}
                    <div class="form-group row">
                        <label for="password_confirmation"
                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="password">
                        </div>
                    </div>
                    <hr>

                    <h5 class="card-title text-center">Profile</h5>
                    {{-- First name --}}
                    <div class="form-group row">
                        <label for="first_name"
                            class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="first_name" id="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}" required autocomplete="name">
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
                                value="{{ old('family_name') }}" required autocomplete="name">

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
                                {{-- <input type="file" name="avatar" id="avatar" class="custom-file-input">
                                    <label for="avatar" class="custom-file-label">Avatar</label> --}}
                                <input type="file" class="form-control-file" name="avatar" id="avatarFile"
                                    aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file.
                                    Size of image should not be more than 2MB.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="form-group row">
                        <label for="social"
                            class="col-md-4 col-form-label text-md-right">{{ __('Social Media Link') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="social" id="social"
                                class="form-control @error('social')is-invalid @enderror" value="{{ old('social') }}"
                                autocomplete="url">

                            @error('social')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="card-title text-center">Role</h5>
                    <div class="form-group row">
                        <div class="col-md-8 mx-auto">
                            <select name="role" id="role" class="custom-select">
                                <option value="admin" @if($user->hasRole('admin')) selected @endif>Admin</option>
                                <option value="user-admin" @if($user->hasRole('user-admin')) selected @endif>User Admin</option>
                                <option value="user" @if($user->hasRole('user')) selected @endif>User</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group-row mb-3">
                        <div class="col-md-8 mx-auto">
                            <button type="submit" class="btn btn-primary btn-block">Create new Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <form type="POST" action="{{ route('users.update', $user) }}">
        @method('PATCH')
        @csrf

        {{-- User settings section --}}
        @can('edit users')
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">User Settings</div>
                    <div class="card-body">
                        {{-- Email input --}}
                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') ? old('email') : $user->email}}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        {{-- Old Password input --}}
                        <div class="form-group row">
                            <label for="old_password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Old Password') }}</label>
                            <div class="col-md-6">
                                <input id="old_password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="old_password"
                                    autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- Password input --}}
                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="new-password">
                            </div>
                        </div>

                        {{-- Password confirm input --}}
                        <div class="form-group row">
                            <label for="password_confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control"
                                    name="password_confirm" required autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        {{-- End user settings section --}}

        {{-- Profile settings section --}}
        @can('edit profiles')
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Profile Settings</div>
                    <div class="card-body">
                        {{-- First name --}}
                        <div class="form-group row">
                            <label for="first_name"
                                class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="first_name" id="first_name"
                                    class="form-control @error('first_name')is-invalid @enderror"
                                    value="{{ old('first_name') ? old('first_name') : $user->profile->first_name }}" required
                                    autocomplete="name">

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
                                    value="{{ old('family_name') ? old('family_name') : $user->profile->family_name }}" required
                                    autocomplete="name">

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
                                    <input type="file" name="avatar file" id="avatar" class="custom-file-input">
                                    <label for="avatar" class="custom-file-label">Avatar</label>
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
                                    value="{{ old('social') ? old('social') : $user->profile->social }}" autocomplete="url">

                                @error('social')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        {{-- End profile section --}}
    </form>
</div>
@endsection

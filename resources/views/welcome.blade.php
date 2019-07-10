@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="jumbotron col">
            <h1 class="display-4">{{ config('app.name', 'Laravel') }}</h1>
            <p class="lead">Welcome to crosslink, here you can view amazing bookmarks, created by our community!</p>
            @auth
            @php
            $user = auth()->user();
            @endphp
            <hr class="my-4">
            <p>Welcome {{ $user->name }}</p>
            <p>Your role is:
                @if($user->hasRole('user')) User @php $role='user' @endphp
                @elseif($user->hasRole('user-admin')) User Administrator @php $role='user-admin' @endphp
                @elseif($user->hasRole('admin')) Administrator @php $role='admin' @endphp
                @elseif($user->hasRole('root')) Root @php $role='root' @endphp
                @elseif($user->hasRole('suspended')) Unfortunately your suspended, your actions are limited to that of a
                guest @php $role='suspended' @endphp
                @else You've not been assigned a role
                @endif
            </p>
            @endauth
        </div>
    </div>

    @include('partials.notverified')

    <div class="row">
        <div class="col-sm-6 pt-2">
            <a class="card text-black text-center welcome-card" href="{{ route('bookmarks.index') }}">
                <p class="display-1 pt-4"><i class="fa fa-bookmark" aria-hidden="true"></p></i>
                <div class="card-body">
                    <h5 class="h2">@can('access all bookmarks') Maintain all @endcan Bookmarks</h5>
                </div>
            </a>
        </div>

        <div class="col-sm-6 pt-2">
            <a class="card text-center welcome-card" href="{{ route('tags.index') }}">
                <p class="display-1 pt-4"><i class="fa fa-tags" aria-hidden="true"></p></i>
                <div class="card-body">
                    <h5 class="h2">@can('access all tags') Maintain all @endcan Tags</h5>
                </div>
            </a>
        </div>


        <div class="@auth col-sm-6 @else col @endauth pt-2">
            <a class="card text-center welcome-card" href="{{ route('users.index') }}">
                <p class="display-1 pt-4"><i class="fa fa-users" aria-hidden="true"></p></i>
                <div class="card-body">
                    <h5 class="h2">@if(auth()->check() && $user->hasAnyPermission(['access all users', 'access all accounts'])) Maintain all @endcan Users</h5>
                </div>
            </a>
        </div>

        @auth
        <div class="col-sm-6 pt-2">
            <a class="card text-center welcome-card" href="{{ route('users.show', $user->id) }}">
                <p class="display-1 pt-4"><i class="fa fa-user" aria-hidden="true"></p></i>
                <div class="card-body">
                    <h5 class="h2">My Profile</h5>
                </div>
            </a>
        </div>

        @if($user->can('create', App\User::class))
        <div class="col pt-2">
            <a class="card text-center welcome-card" href="{{ route('users.create') }}">
                <p class="display-1 pt-4"><i class="fa fa-user-plus" aria-hidden="true"></i></p>
                <div class="card-body">
                    <h5 class="h2">Create new Account</h5>
                </div>
            </a>
        </div>
        @endcan
        @endauth
    </div>
</div>
@endsection

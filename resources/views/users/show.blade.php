@extends('layouts.app')

@section('content')
<div class="container">
    @can('edit users')
    @if(auth()->user()->id === $user->id
    || $user->hasPermissionTo('access all users'))
    <div class="row mb-3">
        <div class="col-auto">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit User</a>
        </div>
    </div>
    @endif
    @endcan
    @if(!$user->hasVerifiedEmail())
    @auth
    @if (auth()->user()->id === $user->id)
    @include('partials.notverified')
    @else
    <div class="row">
        <div class="col">
            <div class="alert alert-warning" role="alert">This user is not verified</div>
        </div>
    </div>
    @endif
    @else
    <div class="row">
        <div class="col">
            <div class="alert alert-warning" role="alert">This user is not verified</div>
        </div>
    </div>
    @endauth
    @endif
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <img src="https://via.placeholder.com/150"
                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">
        </div>
        <div class="col-md-9 col-lg-10">
            <h1>{{ $user->name }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>Email</p>
            <p>{{ $user->email }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <p>First name</p>
            <p>{{ $user->profile->first_name }}</p>
        </div>
        <div class="col-sm-6">
            <p>Family name</p>
            <p>{{ $user->profile->family_name }}</p>
        </div>
    </div>
</div>
@endsection

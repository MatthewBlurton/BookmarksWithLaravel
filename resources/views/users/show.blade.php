@extends('layouts.app')

@section('content')
<div class="container">
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
        @if(is_null($user->profile->avatar))
        <div class="col-md-3 col-lg-2">
            <img src="https://via.placeholder.com/150"
                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                style="max-height:150px; max-width:150px;" alt="User Avatar">
        </div>
        @else
        <div class="col-md-3 col-lg-2">
            <img src="{{ $user->profile->avatar }}"
                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                style="max-height:150px; max-width:150px;" alt="User Avatar">
        </div>
        @endif
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

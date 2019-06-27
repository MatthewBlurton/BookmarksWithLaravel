@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col">
				<h1>Users</h1>
			</div>
		</div>
		<div class="row">
			@foreach($users as $aUser)
			@php($profile = $aUser->profile)
			<div class="col-md-4">
				<div class="card">
				  <img class="card-img-top" src="{{ $profile->avatar }}" alt="User avatar">
				  <div class="card-body">
				    <a href="users/{{ $aUser->id }}"><h5 class="card-title">{{ $aUser->name }}</h5></a>
				    <p class="card-text">{{ $profile->first_name }}, {{ $profile->last_name }}</p>
				  </div>
				  <ul class="list-group list-group-flush">
				  	<li class="list-group-item">
				  		<h6 class="card-subtitle">Actions</h6>
				  		<a href="users/{{ $aUser->id }}/edit" class="btn btn-primary">Modify</a>
				  	</li>
				  </ul>
				  <div class="card-body">
				    <a href="mailto://{{ $profile->email }}" class="card-link">{{ $profile->email }}</a>
				  </div>
				  @if($profile->social)
					  <div class="card-footer">
					  	<a href="{{ $profile->social }}">{{ $profile->social }}</a>
					  </div>
				  @endif
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endsection
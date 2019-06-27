@extends('layouts.app')

@section('content')
<div class="container">
	@if(!$user->hasVerifiedEmail())
	<div class="row">
		<div class="col">
			<div class="alert alert-warning" role="alert">Your not verified, please check your mail</div>
		</div>
	</div>
	@endif
    <div class="row">
		<div class="col-md-3 col-lg-2">
			<img src="https://via.placeholder.com/150" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">
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
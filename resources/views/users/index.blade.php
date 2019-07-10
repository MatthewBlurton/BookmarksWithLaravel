@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>
@endsection

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
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="card mb-4">
					<div class="row justify-content-center">
						@if(isset($profile->avatar))
						<img class="card-img-top mt-4" src="{{ $profile->avatar }}" alt="User avatar" style="max-height:150px; max-width:150px;">
						@else
						<img src="https://via.placeholder.com/150x150" alt="User avatar placeholder" class="card-img-top mt-4" style="max-height:150px; max-width:150px;">
						@endif
					</div>
				  <div class="card-body">
                    <a href="users/{{ $aUser->id }}"><h5 class="card-title">{{ $aUser->name }}</h5></a>
                    @can('viewSensitive', $aUser)
                        <p class="card-text">{{ $profile->first_name }}, {{ $profile->family_name }}</p>
                    @endcan
                  </div>
                  @can('viewSensetive', $aUser)
				  <hr>
				  <div class="card-body">
				    <a href="mailto://{{ $aUser->email }}" class="card-link">{{ $aUser->email }}</a>
				  </div>
                  @endcan

				  @if($profile->social)
				  <hr>
				  <div class="card-body">
				    <a href="{{ $profile->social }}">{{ $profile->social }}</a>
				  </div>
				  @endif

				  {{-- if the user has permission to access all users or write to users display an option to modify the user --}}
				  @can('update', $aUser)
				  @if(!auth()->user()->hasRole('suspended'))
				  	<div class="card-footer">
						  <a href="{{ route('users.edit', $aUser) }}" class="btn btn-primary btn-block">Modify</a>
					</div>
				  @endif
				  @endcan
				</div>
			</div>
			@endforeach
		</div>

		@if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="row justify-content-center">
            <div class="col-auto">{{ $users->links() }}</div>
        </div>
		@endif
	</div>
@endsection

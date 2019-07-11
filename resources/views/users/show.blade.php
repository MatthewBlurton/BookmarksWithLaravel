@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="container">
    {{-- Account information --}}
    <div class="row">
        <div class="col jumbotron">
            <div class="row">
                <div class="col-md-3 col-lg-2 my-auto">
                    @php
                        // Assign the avatar source before creating the image
                        $avatar = $user->profile->avatar;
                        $imageSource = isset($avatar) ? $avatar : "https://via.placeholder.com/150";
                    @endphp
                    <img src="{{ $imageSource }}"
                        class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                        style="max-height:150px; max-width:150px;" alt="User Avatar">
                </div>
                <div class="col">
                    <h1 class="display-4">{{ $user->name }}</h1>
                    @can('viewSensitive', $user)
                    <h2 class="display-5">{{ $user->profile->first_name }}@if($user->profile->family_name){{ __(', ') . $user->profile->family_name }}@endif</h2>
                    @endcan
                </div>
            </div>
            @if((auth()->check() && auth()->user()->can('viewSensitive', $user))
                    || $user->profile->social)
            <div class="row">
                <div class="col">
                    <hr>
                    @can('viewSensitive', $user)
                    <p><strong class="lead">Email:</strong> <a href="mailto:{{$user->email}}">{{$user->email}}</a></p>
                    @endcan
                    @if($user->profile->social)
                    <strong class="lead">Social Media:</strong> <a href="{{$user->profile->social}}">{{$user->profile->social}}</a></p>
                    @endif
                </div>
            </div>
            @endif

            @can('update', $user)
            <div class="row">
                <div class="col">
                    <hr>
                    <a class="btn btn-primary btn-lg btn-block mb-0" href="{{route('users.edit', $user)}}">Edit Account</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    {{-- Owned Bookmarks --}}
    @if ($user->bookmarks->count() > 0)
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($bookmarks as $aBookmark)
                        <li class="list-group-item">
                            <div class="row no-gutters">
                                <div class="col">
                                    <h5 class="card-title">
                                        <a href="{{ route('bookmarks.show', $aBookmark) }}"
                                            class="card-link">{{ $aBookmark->title }}</a>
                                    </h5>
                                    <a href="{{ $aBookmark->url }}">{{ $aBookmark->url }}</a>
                                    <p>{{ $aBookmark->description }}</p>
                                </div>
                                <div class="col-auto mx-4 my-auto">
                                    <strong>
                                        <i class="fa @if($aBookmark->is_public){{__('fa-eye')}}@else{{__('fa-eye-slash')}}@endif" aria-hidden="true"></i>
                                    </strong>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        @if($bookmarks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <li class="list-group-item">
                            <div class="row no-gutters justify-content-center">
                                <div class="col-auto justify-content-center">
                                    {{ $bookmarks->links() }}
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

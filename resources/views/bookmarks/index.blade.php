@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bookmarks</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>
        @cannot('create', App\Bookmark::class)
        10 most recent
        @endcannot
        Bookmarks
        </h1>
    </div>
    <div class="row justify-content-center mb-2">
        <div class="col-8">
            @can('create', App\Bookmark::class)<a href="/bookmarks/create" class="btn btn-primary btn-block">Create</a>@endcan
        </div>
    </div>

    <div class="row mb-3">
        <table class="table-striped col">
            <thead class="thead-dark">
                <tr>
                    @auth @if(auth()->user()->hasVerifiedEmail()) <th scope="col">Visibility</th>@endif @endauth
                    <th scope="col">Name</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookmarks as $aBookmark)
                <tr>
                    @auth @if(auth()->user()->hasVerifiedEmail()) <td scope="col">@if($aBookmark->is_public) Public @else Private @endif</td>@endif @endauth
                    <td scope="col"><a href="{{ $aBookmark->url }}">{{ $aBookmark->title }}</a></td>
                    <td scope="col">
                        <a href="{{ route('users.show', $aBookmark->user_id) }}">{{ $aBookmark->user->name }}</a>
                    </td>
                    <td scope="col">
                        <a href="{{ route('bookmarks.show', $aBookmark->id) }}" class="btn btn-primary">Details</a>
                        @can('update', $aBookmark)
                        <a href="{{ route('bookmarks.edit', $aBookmark->id) }}" class="btn btn-secondary">Edit</a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bookmarks instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="row justify-content-center">
        <div class="col-auto">{{ $bookmarks->links() }}</div>
    </div>
    @endif

</div>
@endsection

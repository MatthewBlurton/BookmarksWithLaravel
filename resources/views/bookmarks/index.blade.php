@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>Bookmarks</h1>
    </div>
    <div class="row mb-2">
        <div class="col-sm-2">
            <a href="/bookmarks/create" class="btn btn-primary">Create</a>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" id="search" placeholder="Search...">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-secondary">Search</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="show_owned" id="show_owned" class="custom-control-input">
                <label class="custom-control-label" for="show_owned">Show owned bookmarks only</label>
            </div>
        </div>
    </div>

    <div class="row">
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
                        @auth @if(auth()->user()->id === $aBookmark->user->id
                                    || auth()->user()->hasPermissionTo('access all bookmarks'))
                                    <a href="{{ route('bookmarks.edit', $aBookmark->id) }}" class="btn btn-secondary">Edit</a> @endif @endauth
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bookmarks instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="row">
        <div class="col text-center">{{ $bookmarks->links() }}</div>
    </div>
    @endif

</div>
@endsection

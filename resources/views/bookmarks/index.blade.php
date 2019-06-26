@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>Bookmarks</h1>
    </div>
    <div class="row justify-content-center">
        <a href="/bookmarks/create" class="btn btn-primary">Create Bookmark</a>
    </div>
    <ul class="list-unstyled">
        @foreach ($bookmarks as $aBookmark)
        <li class="row">
            <b class="col-1">{{ $aBookmark->id}}</b>
            <span class="col"><a href="{{ $aBookmark->url }}">{{ $aBookmark->title }}</a></span>
            <a href="/bookmarks/{{ $aBookmark->id }}" class="col">Details</a>
            <a href="/bookmarks/{{ $aBookmark->id }}/edit" class="btn btn-primary">Edit</a>
        </li>
        @endforeach
    </ul>

</div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <a href="/bookmarks" class="btn btn-primary">< Back</a>
    </div>

    <div class="row">
        <div class="col">
            <h1>Title</h1>
            <p>{{ $bookmark->title }}</p>
            <h2>URL</h2>
            <a href="{{ $bookmark->url }}">{{ $bookmark->url }}</a>
            <h2>Description</h2>
            <p>{{ $bookmark->description }}</p>
            <h2>Tags</h2>
            <ul>
                @foreach ($bookmark->tags as $aTag)
                    <li>{{ $aTag->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

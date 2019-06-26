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
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container-fluid p-0 m-0">
                <div class="row">
                    <form method="POST" action="/tags/{{ $bookmark->id }}" class="col-sm-6">
                        @method("PATCH")
                        @csrf
                        
                        <h2>Tags</h2>
                        <div class="input-group">
                            <input type="text" id="bookmark-tags" class="form-control" placeholder="Enter tag to add"
                                list="tag-list" name="name">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">+</button>
                            </div>
                        </div>
                        <datalist id="tag-list">
                            @foreach (App\Tag::all() as $tag)
                            <option value="{{ $tag->name }}">
                            @endforeach
                        </datalist>
                    </form>
                    <div class="col-sm-6">
                        <ul class="list-group">
                            @foreach ($bookmark->tags as $aTag)
                            <form method="POST" action="/tags/{{ $bookmark->id }}/{{ $aTag->id }}">
                                @method("DELETE")
                                @csrf
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="badge badge-secondary"><strong>{{ $aTag->name }}</strong></span>
                                    <button type="submit" class="btn btn-danger">X</button>
                                </li>
                            </form>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

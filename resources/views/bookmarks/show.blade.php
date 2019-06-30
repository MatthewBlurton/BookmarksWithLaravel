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
            <h2>Owner</h2>
            <a href="{{ route('users.show', $bookmark->user->id) }}">{{ $bookmark->user->name }}</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>Visibility</h2>
            <p>@if($bookmark->is_public) Public @else Private @endif</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="container-fluid">
                <div class="row">
                    <h2>Tags</h2>
                </div>
                @can('update', $bookmark)
                <div class="row">
                    <form method="POST" action="/tags/{{ $bookmark->id }}" class="col-sm-6">
                        @method("PATCH")
                        @csrf
                        <div class="input-group mb-3">
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
                                <p class="h4"><a href="{{ route('tags.show', $aTag) }}" class="badge badge-secondary"><strong>{{ $aTag->name }}</strong></a></p>
                                    <button type="submit" class="btn btn-danger">X</button>
                                </li>
                            </form>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="row">
                        <div class="col">
                            <ul class="list-group">
                                @foreach ($bookmark->tags as $aTag)
                                <li class="list-group-item"><a href="{{ route('tags.show', $aTag) }}" class="badge badge-secondary"><strong>{{ $aTag->name }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

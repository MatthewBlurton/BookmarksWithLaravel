@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col">
            @include('partials.errors')
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form method="POST" action="/bookmarks/{{ $bookmark->id }}" id="update-form">
                @method('PATCH')
                @csrf

                <div class="form-group">
                    <label for="bookmark-title">Title</label>
                    <input type="text" name="title" id="bookmark-title" class="form-control"
                        value="{{ old('title') ? old('title') : $bookmark->title }}" aria-describedBy="titleHelp"
                        placeholder="Enter bookmark title">
                </div>
                <div class="form-group">
                    <label for="bookmark-url">URL</label>
                    <input type="text" name="url" id="bookmark-url" class="form-control"
                        value="{{ old('url') ? old('url') : $bookmark->url }}" aria-describedBy="bookmarkURL"
                        placeholder="Enter URL">
                </div>
                <div class="form-group">
                    <label for="bookmark-description">Description</label>
                    <textarea name="description" id="bookmark-description" class="form-control"
                        aria-describedBy="descriptionHelp"
                        placeholder="Enter Description">{{ old('description') ? old('description') : $bookmark->description }}</textarea>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" action="/bookmarks/{{ $bookmark->id }}" id="delete-form">
        @method("DELETE")
        @csrf
    </form>

    <div class="row">
        <div class="col">
            <button type="submit" class="btn btn-primary" form="update-form">Update</button>
            <button type="submit" class="btn btn-danger" form="delete-form">Delete</button>
        </div>
    </div>
</div>
@endsection

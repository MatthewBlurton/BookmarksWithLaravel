@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('bookmarks.index') }}">Bookmarks</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Bookmark</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            @component('components.errors')
                <strong>Whoops!</strong> Could not change {{$bookmark->title}} for the following reasons.
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form method="POST" action="/bookmarks/{{ $bookmark->id }}" id="update-form">
                @method('PATCH')
                @csrf

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title') ? old('title') : $bookmark->title }}" aria-describedBy="titleHelp"
                        placeholder="Enter bookmark title">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" id="url" class="form-control"
                        value="{{ old('url') ? old('url') : $bookmark->url }}" aria-describedBy="bookmarkURL"
                        placeholder="Enter URL">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"
                        aria-describedBy="descriptionHelp"
                        placeholder="Enter Description">{{ old('description') ? old('description') : $bookmark->description }}</textarea>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_public" id="is_public" class="custom-control-input"
                        @if(old('is_public'))checked @elseif($bookmark->is_public)checked @endif>
                        <label class="custom-control-label" for="is_public">Is Public</label>
                    </div>
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

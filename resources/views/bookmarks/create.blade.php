@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col">
            @component('components.errors')
                <strong>Whoops!</strong> Could not create a new bookmark for the following reasons.
            @endcomponent
        </div>
    </div>
    <form method="POST" action="/bookmarks">
        @csrf
        <div class="form-group row">
            <label for="bookmark-title" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" id="bookmark-title" class="form-control" value="{{ old('title') }}"
                    aria-describedBy="titleHelp" placeholder="Enter bookmark title">
            </div>
        </div>
        <div class="form-group row">
            <label for="bookmark-url" class="col-sm-2 col-form-label">URL</label>
            <div class="col-sm-10">
                <input type="url" name="url" id="bookmark-url" class="form-control" value="{{ old('url') }}"
                    aria-describedBy="bookmarkURL" placeholder="Enter URL">
            </div>
        </div>
        <div class="form-group row">
            <label for="bookmark-description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea name="description" id="bookmark-description" class="form-control"
                    aria-describedBy="descriptionHelp"
                    placeholder="Enter Description">{{ old('description') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Done</button>
    </form>
</div>
@endsection

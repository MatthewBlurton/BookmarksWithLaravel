@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Tags</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $tag->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row no-gutters">
                    <div class="col">
                        <h5 class="card-title">{{ $tag->name }}</h5>
                        <h6 class="card-subtitle">Websites</h5>
                    </div>
                    @can('delete', $tag)
                    <form action="{{ route('tags.destroy', $tag) }}" method="post" class="col-auto">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger">Delete Tag</a>
                    </form>
                    @endcan
                </div>
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
                            </div>
                            <!-- if the user is logged in and either owns the bookmark, or has permission to access all tags
                                give the user the option to remove the tag -->
                            @auth
                                @if(auth()->user()->id === $aBookmark->user_id
                                    || auth()->user()->hasPermissionTo('access all tags'))
                                    <form action="{{ route('bookmarks.tag.detach', ['bookmark' => $aBookmark, 'tag' => $tag]) }}" method="post" class="col-auto">
                                        @method("delete")
                                        @csrf
                                        <button class="btn btn-danger">Remove</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </li>
                    @endforeach
                </ul>
                @if($bookmarks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-body justify-content-center">
                    {{ $bookmarks->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

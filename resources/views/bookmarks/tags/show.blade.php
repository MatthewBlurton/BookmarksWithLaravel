@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col text-center">
            <h1>Tags</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $tag->name }}</h5>
                    <h6 class="card-subtitle">Websites</h5>
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
                                <div class="col-auto">
                                    <button disabled="disabled" class="btn btn-danger">Remove</button>
                                </div>
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

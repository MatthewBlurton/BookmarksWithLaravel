@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bookmarks</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>
            @cannot('create', App\Bookmark::class)
            10 most recent
            @endcannot
            Bookmarks
        </h1>
    </div>
    <div class="row justify-content-center mb-2">
        <div class="col-8">
            @can('create', App\Bookmark::class)<a href="/bookmarks/create"
                class="btn btn-primary btn-block">Create</a>@endcan
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
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
                                    <p>{{ $aBookmark->description }}</p>
                                </div>
                                <div class="col-auto mx-4 my-auto">
                                    <strong>
                                        <i class="fa @if($aBookmark->is_public){{__('fa-eye')}}@else{{__('fa-eye-slash')}}@endif" aria-hidden="true"></i>
                                     </strong>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        @if($bookmarks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <li class="list-group-item">
                            <div class="row no-gutters justify-content-center">
                                <div class="col-auto justify-content-center">
                                    {{ $bookmarks->links() }}
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

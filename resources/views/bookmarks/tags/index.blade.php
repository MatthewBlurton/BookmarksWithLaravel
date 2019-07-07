@extends('layouts.app')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tags</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    @if($message = Session::get('success'))
        <div class="row">
            <div class="col">
                <div class="alert alert-success alert-block">
                    <button class="close" data-dismiss="alert">x</button>
                    <strong>{{ $message }}</strong>
                </div>
            </div>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="row">
            <div class="col">
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Ecountered some problems. <br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <h1>Tags</h1>
    </div>

    {{-- Actions --}}
    <div class="row">

    </div>

    <div class="row mb-3">
        <div class="col">
            <p class="h1">
                @foreach ($tags as $aTag)
                <a href="{{ route('tags.show', $aTag) }}" class="h1 badge badge-secondary" >{{ $aTag->name }}</a>
                @endforeach
            </p>
        </div>
    </div>

    @if($tags instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="row justify-content-center">
        <div class="col-auto">
            {{ $tags->links()}}
        </div>
    </div>
    @endif
</div>
@endsection

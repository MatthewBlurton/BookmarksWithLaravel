@extends('layouts.app')

@section('content')
<div class="container">
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

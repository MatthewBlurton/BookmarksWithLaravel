@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading"><strong>Whoops!</strong> {{ $slot }}</h4>
        <hr>
            @foreach ($errors->all() as $aError)
                <p class="mb-0">{{ $aError }}</p>
            @endforeach
    </div>
@endif

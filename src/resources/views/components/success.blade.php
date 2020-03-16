@if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading"><strong>Success!</strong> {{ $slot }}</h4>
        <hr>
        {{ Session::get('success') }}
    </div>
@endif

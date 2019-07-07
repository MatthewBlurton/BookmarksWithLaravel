<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body>
    <div id="app">
        @include('partials.header')
        @yield('breadcrumbs')
        <main class="py-4">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>
</body>

</html>

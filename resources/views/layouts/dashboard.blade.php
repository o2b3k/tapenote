<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-green.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    @stack('styles')
</head>

<body>
    <div class="main-wrapper">
        <div id="app" class="app">
            @include('blocks.header')
            @include('blocks.sidebar')

            @yield('content')

            @include('blocks.footer')
        </div>
    </div>
    <!-- Reference block for JS. Do not remove -->
    <div class="ref" id="ref">
        <div class="color-primary"></div>
        <div class="chart">
            <div class="color-primary"></div>
            <div class="color-secondary"></div>
        </div>
    </div>

    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    @if (session('status'))
        <?php $status = session('status'); ?>
        <script>
            @if (isset($status['title']))
                toastr.{{ $status['type'] }}('{{ $status['message'] }}', '{{ $status['title'] }}');
            @else (isset($status['title']) || isset())
                toastr.{{ $status['type'] }}('{{ $status['message'] }}');
            @endif
        </script>
    @endif
</body>
</html>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Downloads scheduler</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="app">
        @include('copyright')
        @include('download-panel')
        @include('download-list')
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('footer-script')
    <script>
        let vm = new Vue({
            mixins : mixins,
            el: '#app',
            data: null
        });
    </script>
</body>
</html>

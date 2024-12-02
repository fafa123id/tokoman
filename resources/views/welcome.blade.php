<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png">
    <title>{{ config('app.name', 'Tokoman') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div id="mainApp" class=" bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        <div class="bg-slate-300 dark:bg-gray-900">
            @include('layouts.toko')
            @include('layouts.LandingContent')
        </div>
        @include('layouts.footer')
    </div>
</body>
<script src="https://tokoman.s3.ap-southeast-2.amazonaws.com/js/TokomanScript.js"></script>
@auth
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://tokoman.s3.ap-southeast-2.amazonaws.com/js/modalPusher.js"></script>
    <script src="https://tokoman.s3.ap-southeast-2.amazonaws.com/js/pusherLogout.js"></script>
@endauth
<!-- <script>
    window.onload = function() {
        if(performance.navigation.type == 2) {
            location.reload(true);
        }
    }
</script> -->

</html>
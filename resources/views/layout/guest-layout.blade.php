<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @filamentStyles

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="{{ asset('css/swiper/swiper-bundle.min.css') }}">

    <title>certifyNow</title>
</head>

<body style="background-color: #CEFAFE;">
    @livewire('notifications')

    @include('components.header')

    <!-- Main Content -->
    @yield('content')

    @include('components.footer')

    @livewire('notifications')

    @filamentScripts

    @vite('resources/js/app.js')

    <script src="{{ asset('js/swiper/swiper-bundle.min.js') }}"></script>
</body>

</html>

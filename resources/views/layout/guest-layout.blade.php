<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @filamentStyles

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="{{ asset('css/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom/animate.css') }}">

    <title>certifyNow</title>
</head>

<body>
    @livewire('notifications')

    @include('components.header')

    <!-- Main Content -->
    @yield('content')

    @include('components.footer')

    @livewire('notifications')

    @filamentScripts

    @vite('resources/js/app.js')

    <script src="{{ asset('js/alpine/alpine.min.js') }}"></script>
    <script src="{{ asset('js/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/swiper/slider.js') }}"></script>
    <script src="{{ asset('js/custom/animate.js') }}"></script>
</body>

</html>

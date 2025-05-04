<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/png" href="{{ asset('other/favicon.ico') }}" />

    @filamentStyles

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="{{ asset('css/swiper/swiper-bundle.min.css') }}">

    <title>certify</title>
</head>

<body>
    @livewire('notifications')

    @include('components.header')

    <!-- Main Content -->
    @yield('content')

    @include('components.footer')

    @livewire('notifications')

    @filamentScripts
    @livewireScripts

    <script src="{{ asset('js/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/swiper/slider.js') }}"></script>

    @vite('resources/js/app.js')
</body>

</html>

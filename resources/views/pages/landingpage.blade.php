@extends('layout.guest-layout')

@section('content')
    <div class="flex flex-col space-y-40">
        {{-- about us --}}
        @include('homepage.top-section')

        {{-- online learning --}}
        @include('homepage.gallery')

        {{-- start study now --}}
        @include('homepage.action')

        {{-- why chose us --}}
        @include('homepage.getting-started')

        {{-- faq --}}
        @include('homepage.faq')
    </div>
@endsection

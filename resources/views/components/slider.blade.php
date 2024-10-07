<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="{{ asset('css/slider/swiper-bundle.min.css') }}">

<!-- Demo styles -->
<style>
    .swiper {
        width: 100%;
        height: auto;
        overflow: hidden;
    }

    .swiper-wrapper {
        max-width: 800px;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right,
                rgba(182, 182, 182, 0.2) 0%,
                rgba(182, 182, 182, 0.2) 10%,
                rgba(0, 0, 0, 0) 20%,
                rgba(0, 0, 0, 0) 80%,
                rgba(182, 182, 182, 0.2) 90%,
                rgba(182, 182, 182, 0.2) 100%);
        z-index: 15;
    }

    .swiper-slide img {
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        position: relative;
        z-index: 20;
    }

    @media (min-width: 300px) {
        .swiper-slide img {
            max-height: 180px;
        }

        .swiper-wrapper {
            max-width: 200px;
        }
    }

    @media (min-width: 560px) {
        .swiper-slide img {
            max-height: 320px;
        }
    }

    @media (min-width: 768px) {
        .swiper-slide img {
            max-height: 360px;
        }
    }

    @media (min-width: 1024px) {
        .swiper-slide img {
            max-height: 400px;
        }
    }

    @media (min-width: 1280px) {
        .swiper-slide img {
            max-height: 420px;
        }
    }

    .swiper-nav-left,
    .swiper-nav-right {
        position: absolute;
        top: 0;
        width: 10%;
        height: 100%;
        z-index: 10;
        cursor: pointer;
    }

    .swiper-nav-left {
        left: 0;
    }

    .swiper-nav-right {
        right: 0;
    }
</style>

<div class="overflow-hidden w-full max-w-full">
    <div class="swiper mySwiper max-w-full overflow-hidden">
        <div class="swiper-nav-left"></div>
        <div class="swiper-nav-right"></div>
        <div class="swiper-wrapper rounded-lg">
            @foreach ($gallery as $image)
                <div class="swiper-slide">
                    <a href="{{ asset($image) }}" target="_blank">
                        <img src="{{ asset($image) }}" alt="" class="">
                    </a>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next-container">
            <div class="swiper-button-next"></div>
        </div>
        <div class="swiper-button-prev-container">
            <div class="swiper-button-prev"></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="/js/slider/slider.js"></script>

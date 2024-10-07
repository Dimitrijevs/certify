document.addEventListener("DOMContentLoaded", function () {
    var swiper = new Swiper(".mySwiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    document
        .querySelector(".swiper-nav-left")
        .addEventListener("click", function () {
            swiper.slidePrev();
        });

    document
        .querySelector(".swiper-nav-right")
        .addEventListener("click", function () {
            swiper.slideNext();
        });
});

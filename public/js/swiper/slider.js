document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".swiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },

        scrollbar: {
            el: ".swiper-scrollbar",
        },

        autoplay: {
            delay: 4000,
        },

        effect: "cards",
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

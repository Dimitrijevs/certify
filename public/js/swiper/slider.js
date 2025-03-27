document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".swiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true,
        scrollbar: {
            el: ".swiper-scrollbar",
            draggable: true,
        },
        autoplay: {
            delay: 6000,
        },
        effect: "slide",
    });
});
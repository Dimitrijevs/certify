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

    const navLeft = document.querySelector(".swiper-nav-left");
    const navRight = document.querySelector(".swiper-nav-right");

    if (navLeft) {
        navLeft.addEventListener("click", function () {
            swiper.slidePrev();
        });
    }

    if (navRight) {
        navRight.addEventListener("click", function () {
            swiper.slideNext();
        });
    }
});

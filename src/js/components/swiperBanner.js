import Swiper from "swiper/bundle";

export default function SwiperBanner() {
    var myBanner;

    function resetSlideTransform() {
        if (myBanner && myBanner.slides) {
            myBanner.slides.forEach(slide => {
                slide.style.transform = "none";
            });
        }
    }

    function handleSlideTransition(activeIndex, direction) {
        var slides = myBanner.slides;
        var slideLength = slides.length;
        var prevIndex = (activeIndex === 0) ? slideLength - 1 : activeIndex - 1;
        var nextIndex = (activeIndex === slideLength - 1) ? 0 : (activeIndex + 1) % slideLength;
        var targetIndex = (direction === "prev") ? prevIndex : nextIndex;
        var targetOffset = (direction === "prev") ? "120px" : "-120px";

        slides[targetIndex].style.transform = "translateX(" + targetOffset + ")";
        slides[targetIndex].style.zIndex = "30";
        slides[activeIndex].style.transform = "translateX(" + (targetOffset === "120px" ? "20px" : "-20px") + ")";
        slides[activeIndex].style.zIndex = "10";
    }

    function handleMouseOver(direction) {
        handleSlideTransition(myBanner.activeIndex, direction);
    }

    function handleMouseOut(direction) {
        var activeIndex = myBanner.activeIndex;
        var slides = myBanner.slides;
        var slideLength = slides.length;
        var prevIndex = (activeIndex === 0) ? slideLength - 1 : activeIndex - 1;
        var nextIndex = (activeIndex === slideLength - 1) ? 0 : (activeIndex + 1) % slideLength;

        var prevSlide = slides[prevIndex];
        var nextSlide = slides[nextIndex];

        if (direction === "prev" && prevSlide) {
            prevSlide.style.transform = "translateX(0)";
            prevSlide.style.zIndex = "10";
            slides[activeIndex].style.transform = "translateX(0)";
            slides[activeIndex].style.zIndex = "10";
        } else if (direction === "next" && nextSlide) {
            nextSlide.style.transform = "translateX(0)";
            nextSlide.style.zIndex = "10";
            slides[activeIndex].style.transform = "translateX(0)";
            slides[activeIndex].style.zIndex = "10";
        }
    }

    function initializeSwiper() {
        myBanner = new Swiper(".banner .swiper", {
            loop: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            initialSlide: 0,
            speed: 1000,
            watchOverflow: false,
            pagination: {
                el: ".banner .swiper-pagination",
                clickable: true,
            },

            breakpoints: {
                1200: {
                    navigation: {
                        nextEl: ".banner .swiper-button-next",
                        prevEl: ".banner .swiper-button-prev",
                    },
                },
                0: {
                    navigation: {
                        nextEl: null,
                        prevEl: null,
                    },
                }
            },

            on: {
                init: function () {
                    document.querySelector(".banner .swiper-left").addEventListener("mouseover", function () {
                        handleMouseOver("prev");
                    });

                    document.querySelector(".banner .swiper-left").addEventListener("mouseout", function () {
                        handleMouseOut("prev");
                    });

                    document.querySelector(".banner .swiper-right").addEventListener("mouseover", function () {
                        handleMouseOver("next");
                    });

                    document.querySelector(".banner .swiper-right").addEventListener("mouseout", function () {
                        handleMouseOut("next");
                    });

                    resetSlideTransform();
                },
                slideChange: function () {
                    resetSlideTransform();
                }
            },
        });
    }

    initializeSwiper();

    document.querySelector(".swiper-left")?.addEventListener("click", function () {
        myBanner.slidePrev();
        handleSlideTransition(myBanner.activeIndex, "prev");
    });

    document.querySelector(".swiper-right")?.addEventListener("click", function () {
        myBanner.slideNext();
        handleSlideTransition(myBanner.activeIndex, "next");
    });
}

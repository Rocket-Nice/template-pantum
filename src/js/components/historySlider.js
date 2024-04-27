import Swiper from "swiper/bundle";
export default function SwiperHistory() {
  let thumbsSlider = new Swiper(".thumbs-slider", {
    freeMode: true,
    slidesPerView: 2,
    watchSlidesProgress: true,
    speed: 1000,

    breakpoints: {
      481: {
        slidesPerView: 3,
      },

      641: {
        slidesPerView: 4,
      },

      769: {
        slidesPerView: 6,
      },
    },
  });

  let historySlider = new Swiper(".history-slider", {
    slidesPerView: "auto",
    spaceBetween: 20,
    speed: 1000,
    autoplay: {
      delay: 8000,
    },
    navigation: {
      nextEl: " .history-button-next",
      prevEl: ".history-button-prev",
    },

    thumbs: {
      swiper: thumbsSlider,
    },
  });
}

import Swiper from "swiper/bundle";
export default function swiperDeviceCard() {
  let thumbsSlider = new Swiper(".device-thumbs__slider", {
    slidesPerView: 4,
    watchSlidesProgress: true,
    speed: 300,

    breakpoints: {
      769: {
        slidesPerView: 5,
      },
    },
  });

  let deviceSlider = new Swiper(".device-slider", {
    slidesPerView: "auto",
    effect: "fade",
    speed: 300,

    navigation: {
      nextEl: " .device-thumbs-next",
      prevEl: ".device-thumbs-prev",
    },

    thumbs: {
      swiper: thumbsSlider,
    },
  });
}

import Swiper from "swiper/bundle";

export default function swiperDeviceMaterial() {
  let materialSlider = new Swiper(".device-material__slider", {
    slidesPerView: 2,
    spaceBetween: 10,
    speed: 1000,

    breakpoints: {
      641: {
        slidesPerView: 3,
      },

      769: {
        slidesPerView: 4,
      },

      801: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
    },

    pagination: {
      el: ".device-material-pagination",
      clickable: true,
    },
  });
}

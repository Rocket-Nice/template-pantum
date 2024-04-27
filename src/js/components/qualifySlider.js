import Swiper from "swiper/bundle";

export default function SwiperQualify() {
  let qualify = new Swiper(".qualify-slider", {
    slidesPerView: "2",
    slidesPerGroup: "2",
    spaceBetween: 10,
    speed: 1000,

    pagination: {
      el: ".qualify-slider-pagination",
      clickable: true,
    },

    breakpoints: {
      481: {
        slidesPerView: "3",
        slidesPerGroup: "3",
        spaceBetween: 20,
      },
      641: {
        slidesPerView: "4",
        slidesPerGroup: "4",
        spaceBetween: 20,
      },
      769: {
        slidesPerView: "4",
        slidesPerGroup: "4",
        spaceBetween: 60,
      },
    },
  });
}

import Swiper from "swiper/bundle";
export default function SwiperNews() {
  let newsSlider = new Swiper(".news-slider", {
    rewind: true,
    slidesPerView: "auto",
    spaceBetween: 20,
    speed: 1000,

    navigation: {
      nextEl: " .news-slider-button-next",
      prevEl: ".news-slider-button-prev",
    },

    pagination: {
      el: ".news-slider-button-pagination",
      clickable: true,
    },
  });
}

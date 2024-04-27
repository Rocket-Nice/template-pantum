import Swiper from "swiper/bundle";
export default function SwiperAboutNews() {
  let aboutNewsSlider = new Swiper(".news-row", {
    slidesPerView: "auto",
    spaceBetween: 20,
    speed: 500,

    navigation: {
      nextEl: " .news-row-button-next",
      prevEl: ".news-row-button-prev",
    },

    pagination: {
      el: ".news-row-pagination",
      clickable: true,
    },
  });
}

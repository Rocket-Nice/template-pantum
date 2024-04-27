import Swiper from "swiper/bundle";
export default function SwiperFuncBox() {
  var inFunc_box = new Swiper(".inFunc_box .swiper", {
    slidesPerView: 3,
    spaceBetween: 15,
    loop: true,
    freeMode: false,
    watchOverflow: true,
    pagination: {
      el: ".inFunc_box .swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".inFunc_box .swiper-button-next",
      prevEl: ".inFunc_box .swiper-button-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 4,
        spaceBetween: 40,
      },
    },
  });
}

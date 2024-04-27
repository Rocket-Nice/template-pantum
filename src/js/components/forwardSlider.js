import Swiper from "swiper/bundle";

export default function SwiperForward() {
  const sliderContainers = document.querySelectorAll('.forward-slider');

  sliderContainers.forEach(container => {
    const slides = container.querySelectorAll('.forward__item, .swiper-slide');
    const hasEnoughSlides = slides.length > 3;

    // Добавляем или удаляем класс 'no-swiper' в зависимости от количества слайдов
    container.classList.toggle('no-swiper', !hasEnoughSlides);

    if (hasEnoughSlides) {
      document.querySelector('.forward-button-prev').style.display = 'block';
      document.querySelector('.forward-button-next').style.display = 'block';
      // Создаем Swiper только если слайдов достаточно
      new Swiper(container, {
        slidesPerView: "auto",
        speed: 300,
        navigation: {
          nextEl: container.querySelector('.forward-button-next'),
          prevEl: container.querySelector('.forward-button-prev'),
        },
        breakpoints: {
          0: {
            enabled: false,
          },
          541: {
            enabled: true,
            spaceBetween: 7,
          },
          641: {
            spaceBetween: 15,
          },
          1201: {
            spaceBetween: 100,
          },
        },
      });
    }
  });
}

import Swiper from "swiper/bundle";

export default function SwiperProducts() {
  let productsSlider = new Swiper(".products-slider", {
    slidesPerView: "auto",
    loop: true,
    centeredSlides: true,
    autoHeight: true,
    spaceBetween: 20,
    initialSlide: 0,
    observer: true,
    observeParents: true,
    observeSlideChildren: true,
    speed: 500,

    navigation: {
      nextEl: ".products-slider-button-next",
      prevEl: ".products-slider-button-prev",
    },

    pagination: {
      el: ".products-slider .news-slider-pagination",
      clickable: true,
    },
    breakpoints: {
      320: {
        spaceBetween: 10,
      },

      641: {
        spaceBetween: 20,
      },
    },
    on: {
      init: function () {
        document
          .querySelector(".prev-slide")
          .addEventListener("mouseover", function () {
            handleMouseOver("prev");
          });

        document
          .querySelector(".prev-slide")
          .addEventListener("mouseout", function () {
            handleMouseOut("prev");
          });

        document
          .querySelector(".next-slide")
          .addEventListener("mouseover", function () {
            handleMouseOver("next");
          });

        document
          .querySelector(".next-slide")
          .addEventListener("mouseout", function () {
            handleMouseOut("next");
          });
      },
    },
  });

  function handleSlideTransition(activeIndex, direction) {
    var slides = productsSlider.slides;
    var slideLength = slides.length;
    var prevIndex = activeIndex === 0 ? slideLength - 1 : activeIndex - 1;
    var nextIndex =
      activeIndex === slideLength - 1 ? 0 : (activeIndex + 1) % slideLength;
    var targetIndex = direction === "prev" ? prevIndex : nextIndex;
    var targetOffset = direction === "prev" ? "10px" : "-10px";

    var targetSlide = slides[targetIndex];
    if (targetSlide) {
      targetSlide.style.transform = "translateX(" + targetOffset + ")";
      targetSlide.style.zIndex = "30";
      slides[activeIndex].style.transform =
        "translateX(" + (targetOffset === "10px" ? "0px" : "-0px") + ")";
      slides[activeIndex].style.zIndex = "10";
    }
  }

  function handleMouseOver(direction) {
    var activeIndex = productsSlider.activeIndex;
    handleSlideTransition(activeIndex, direction);
  }

  function handleMouseOut(direction) {
    var activeIndex = productsSlider.activeIndex;
    var slides = productsSlider.slides;
    var slideLength = slides.length;
    var prevIndex = activeIndex === 0 ? slideLength - 1 : activeIndex - 1;
    var nextIndex =
      activeIndex === slideLength - 1 ? 0 : (activeIndex + 1) % slideLength;

    var prevSlide = slides[prevIndex];
    var nextSlide = slides[nextIndex];

    if (direction === "prev" && prevSlide) {
      prevSlide.style.transform = "translateX(0)";
      prevSlide.style.zIndex = "10";
      productsSlider.slides[activeIndex].style.transform = "translateX(0)";
      productsSlider.slides[activeIndex].style.zIndex = "10";
    } else if (direction === "next" && nextSlide) {
      nextSlide.style.transform = "translateX(0)";
      nextSlide.style.zIndex = "10";
      productsSlider.slides[activeIndex].style.transform = "translateX(0)";
      productsSlider.slides[activeIndex].style.zIndex = "10";
    }
  }

  document.querySelector(".prev-slide")?.addEventListener("click", function () {
    productsSlider.slidePrev();
    handleSlideTransition(productsSlider.activeIndex, "prev");
  });

  document.querySelector(".next-slide")?.addEventListener("click", function () {
    productsSlider.slideNext();
    handleSlideTransition(productsSlider.activeIndex, "next");
  });
}

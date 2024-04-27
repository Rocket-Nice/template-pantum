export default function Gotop() {
  // Обработчик события прокрутки окна
  window.addEventListener("scroll", function () {
    // Если прокрутка больше 100px
    if (window.pageYOffset > 100) {
      // Добавить класс "active" к элементу с классом "gotop"
      document.querySelector(".gotop")?.classList.add("active");
    } else {
      // Удалить класс "active" у элемента с классом "gotop"
      document.querySelector(".gotop")?.classList.remove("active");
    }
  });

  // Обработчик события клика по элементу с классом "gotop"
  document.querySelector(".gotop")?.addEventListener("click", function () {
    // Анимация прокрутки вверх
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
}

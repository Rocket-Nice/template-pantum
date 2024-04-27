export default function increaseCounter() {
  const counters = document.querySelectorAll(".counter");
  const wrapTop = document.querySelector(".about")?.offsetTop;
  let istrue = true;

  window.addEventListener("scroll", () => {
    var scrollHeight = window.pageYOffset + 150;
    if (scrollHeight > wrapTop && istrue) {
      counters.forEach((counter) => {
        let start = parseInt(counter.textContent.trim(), 10); // Получаем содержимое блока и преобразуем в число
        let step = Number(counter.dataset.step);
        let time = Math.round(
          counter.dataset.speed / (counter.dataset.to / step)
        );
        let interval = setInterval(() => {
          start += step;
          if (start >= counter.dataset.to) {
            clearInterval(interval);
            start = parseInt(counter.dataset.to); // Убедимся, что старт равен конечному значению
          }
          counter.textContent = start; // Обновляем текстовое содержимое блока
        }, time);
      });
      istrue = false;
    }
  });
}

export default function openFaqPopup() {
  const buttons = document?.querySelectorAll(".faq__link");
  const mask = document?.querySelector(".faq-popup-mask");

  buttons.forEach((button) => {
    button.addEventListener("click", (evt) => {
      evt.preventDefault();
      const item = button?.closest(".faq__item");

      const popup = item?.querySelector(".faq-popup");

      popup.style.display = "block";
      mask.style.display = "block";

      const closeButton = popup?.querySelector(".faq-popup__close");

      closeButton.addEventListener("click", () => {
        popup.style.display = "none";
        mask.style.display = "none";
      });

      mask.addEventListener("click", () => {
        popup.style.display = "none";
        mask.style.display = "none";
      });
    });
  });
}

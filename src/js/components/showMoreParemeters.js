const openButton = document?.querySelector(
  ".device-specifications__button--more "
);
const parameters = document?.querySelectorAll(".device-table__row--hidden");

export default function showMoreParameters() {
  openButton?.addEventListener("click", () => {
    parameters.forEach((parametr) => {
      parametr.style.display = "block";
      openButton.style.display = "none";
    });
  });
}

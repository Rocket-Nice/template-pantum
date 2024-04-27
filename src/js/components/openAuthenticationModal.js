const openButtons = document?.querySelectorAll(".authentication-form__link");

const closeSerialModal = document?.querySelector(".authentication-close");
const modalMask = document?.querySelector(".authentication-maskbg");

export default function openSerialNumber() {
  if (window.innerWidth <= 1200) {
    openButtons.forEach((button) => {
      button.addEventListener("click", (evt) => {
        const modalItem = button.closest(".authentication-form__more");
        const serialModal = modalItem.querySelector(".authentication-modal");
        serialModal.style.display = "initial";
        modalMask.style.display = "initial";
        closeSerialModal.style.display = "initial";

        closeSerialModal?.addEventListener("click", () => {
          serialModal.style.display = "none";
          modalMask.style.display = "none";
          closeSerialModal.style.display = "none";
        });
      });
    });
  }
}

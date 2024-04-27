export default function setMaskHeight() {
	const productCards = document?.querySelectorAll(".product-card");
	productCards.forEach((card) => {
		card.addEventListener("mouseenter", () => {
			const mask = card.querySelector(".product-card__mask");
			if (mask.closest(".products-center")) {
				if (window?.innerWidth > 1441) {
					mask.style.height = 95 + "%";
				} else {
					mask.style.height = 100 + "%";
				}
			} else {
				mask.style.height = card.scrollHeight + 10 + "px";
			}
		});
	});
}

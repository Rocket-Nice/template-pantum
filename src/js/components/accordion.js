const accordions = document?.querySelectorAll(".accordion");
const innerAccordions = document?.querySelectorAll(".accordion-inner");
const driverItems = document.querySelectorAll(".popular-queries__item");

export default function openAccordion() {
	accordions.forEach((el) => {
		const control = el.querySelector(".accordion__control");

		control?.addEventListener("click", (evt) => {
			evt.preventDefault();
			const self = el;
			const content = self.querySelector(".accordion__content");

			self.classList.toggle("open");
			if (self.classList.contains("open")) {
				if (!window.location.href.includes("rezultaty-poiska")) {
					control.classList.add("sidebar-left__title--current");
				}

				content.style.display = "block";
				content.style.height = 0;

				setTimeout(() => {
					content.style.height = 200 + "px";
				}, 100);

				setTimeout(() => {
					content.style.height = "";
				}, 400);
			} else {
				if (control.closest(".sidebar-left__item--product")) {
					control.classList.remove("sidebar-left__title--current");
				}
				content.style.height = 200 + "px";
				setTimeout(() => {
					content.style.height = 0 + "px";
				}, 10);
			}
		});
	});

	innerAccordions.forEach((el) => {
		const control = el.querySelector(".accordion-inner__control");

		control.addEventListener("click", () => {
			const self = el;
			const content = self.querySelector(".accordion-inner__content");
			const currentElement = document?.querySelector(".accordion-inner.active");

			if (
				currentElement !== self &&
				currentElement?.classList.contains("active")
			) {
				currentElement.classList.remove("active");
				currentElement.querySelector(
					".accordion-inner__content"
				).style.maxHeight = null;
			}

			self.classList.toggle("active");

			if (self.classList.contains("active")) {
				content.style.maxHeight = content.scrollHeight + "px";
			} else {
				content.style.maxHeight = null;
			}
		});
	});

	driverItems.forEach((item) => {
		const hideContent = item.querySelector(".popular-queries__hide-content");

		item.addEventListener("click", () => {
			const otherActiveItem = document?.querySelector(
				".popular-queries__item.active"
			);
			const driverTitel = item.querySelector(".popular-queries__title");
			if (
				item !== otherActiveItem &&
				otherActiveItem?.classList.contains("active")
			) {
				otherActiveItem?.classList.remove("active");
				otherActiveItem.querySelector(
					".popular-queries__hide-content"
				).style.maxHeight = null;
				otherActiveItem
					.querySelector(".popular-queries__hide-content")
					.classList.remove("visible");
			}

			item.classList.toggle("active");
			if (item.classList.contains("active")) {
				driverTitel.style.marginBottom = 20 + "px";
				hideContent.classList.add("visible");
				hideContent.style.maxHeight = hideContent.scrollHeight + "px";
			} else {
				driverTitel.style.marginBottom = 1 + "px";
				hideContent.style.maxHeight = null;
				hideContent.classList.remove("visible");
			}
		});
	});
}

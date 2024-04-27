import gsap from "gsap";

export default function AnimatedHeader() {
	const header = document.querySelector(".header");
	const mobileMenu = document.querySelector(".mobileMenu");
	const searchFixed = document.querySelector(".search_fixed");

	// Функция для добавления класса "open" к header
	function openHeader() {
		header.classList.add("open");
	}

	// Функция для удаления класса "open" из header
	function closeHeader() {
		header.classList.remove("open");
	}

	// Функция для плавного появления блока search_fixed
	function showSearchFixed() {
		gsap.set(searchFixed, { height: 0, overflow: "hidden", opacity: 0 });
		gsap.to(searchFixed, {
			height: "auto",
			opacity: 1,
			duration: 0.3,
			ease: "power2.inOut",
		});
	}

	// Функция для плавного скрытия блока search_fixed
	function hideSearchFixed() {
		gsap.to(searchFixed, {
			height: 0,
			opacity: 0,
			duration: 0.3,
			ease: "power2.inOut",
		});
	}

	// Обработчик события клика на mobileMenu
	mobileMenu.addEventListener("click", () => {
		// Проверяем, есть ли у header класс "open"
		if (!header.classList.contains("open")) {
			// Если класса нет, вызываем функцию для добавления класса "open"
			openHeader();
		} else {
			// Если класс уже есть, вызываем функцию для удаления класса "open"
			closeHeader();
		}
	});

	header.querySelector(".mobileMask").addEventListener("click", () => {
		closeHeader();
	});

	// Добавляем обработчик события клика на блок с классом search
	const search = document.querySelector(".search");
	search.addEventListener("click", () => {
		if (searchFixed.classList.contains("visible")) {
			hideSearchFixed();
			searchFixed.classList.remove("visible");
			searchFixed.style.pointerEvents = "none";
		} else {
			showSearchFixed();
			searchFixed.classList.add("visible");
			searchFixed.style.pointerEvents = "unset";
		}
	});

	// Проверка ширины экрана
	const screenWidth = window.innerWidth;

	if (screenWidth > 1200) {
		window.addEventListener("scroll", function () {
			if (window.scrollY > 0) {
				header.classList.add("on");
			} else {
				header.classList.remove("on");
			}
		});

		header.addEventListener("mouseenter", () => {
			header.classList.add("on");
		});

		header.addEventListener("mouseleave", () => {
			if (window.scrollY === 0) {
				header.classList.remove("on");
			} else {
				header.classList.add("on");
			}
		});
		const items = header.querySelectorAll(".item");

		let isSubMenuOpen = false; // Добавляем переменную для отслеживания состояния

		items.forEach((item) => {
			const subMenu = item.querySelector(".subMenu");

			if (subMenu) {
				setTimeout(function () {
					subMenu.style.display = "block";

					if (subMenu.style.display === "block") {
						subMenu.style.opacity = 1;
						const subMenuStyles = window.getComputedStyle(subMenu);
						const paddingTop = parseInt(subMenuStyles.paddingTop);
						const paddingBottom = parseInt(subMenuStyles.paddingBottom);
						const scrollHeightInitial = subMenu.scrollHeight;

						gsap.set(subMenu, {
							height: 0,
							paddingTop: 0,
							paddingBottom: 0,
							overflow: "hidden",
						});

						item.addEventListener("mouseenter", () => {
							if (!isSubMenuOpen) {
								// Проверяем, нет ли уже раскрытых подменю
								const height = scrollHeightInitial;
								gsap.to(subMenu, {
									height: height,
									paddingTop: paddingTop,
									paddingBottom: paddingBottom,
									duration: 0,
									ease: "power2.inOut",
								});
							}
						});

						item.addEventListener("mouseleave", () => {
							if (!isSubMenuOpen) {
								// Проверяем, нет ли уже раскрытых подменю
								gsap.set(subMenu, { height: "auto" }); // Устанавливаем высоту в auto перед анимацией
								const height = scrollHeightInitial + paddingTop + paddingBottom;
								gsap.to(subMenu, {
									height: 0,
									paddingTop: 0,
									paddingBottom: 0,
									duration: 0,
									ease: "power2.inOut",
									onComplete: () => {
										gsap.set(subMenu, { height: 0 }); // Устанавливаем высоту обратно в 0 после анимации
									},
								});
							}
						});

						// Добавляем обработчики для отслеживания состояния при наведении и уходе курсора
						subMenu.addEventListener("mouseenter", () => {
							isSubMenuOpen = true;
						});

						subMenu.addEventListener("mouseleave", () => {
							isSubMenuOpen = false;
						});
					}
				}, 100);
			}
		});
	} else {
		// Добавление аккордиона на стрелки в бургере

		const arrowButtons = document.querySelectorAll(".header .arrow");

		arrowButtons.forEach(function (arrowButton) {
			arrowButton.addEventListener("click", function () {
				var parentItem = this.parentElement;
				var subMenu = parentItem.querySelector(".subMenu");

				subMenu.classList.toggle("active");
				parentItem.classList.toggle("on");

				// Плавное открытие subMenu при добавлении класса "on"
				if (parentItem.classList.contains("on")) {
					// Закрыть все остальные subMenu перед открытием нового
					document
						.querySelectorAll(".header .subMenu")
						.forEach(function (otherSubMenu) {
							if (
								otherSubMenu !== subMenu &&
								otherSubMenu.classList.contains("active")
							) {
								otherSubMenu.classList.remove("active");
								otherSubMenu.parentElement.classList.remove("on");
								gsap.to(otherSubMenu, {
									height: 0,
									duration: 0.3,
									ease: "power2.inOut",
								});
							}
						});
					gsap.to(subMenu, {
						height: "auto",
						duration: 0.3,
						ease: "power2.inOut",
					});
				} else {
					// Плавное закрытие subMenu при удалении класса "on"
					gsap.to(subMenu, { height: 0, duration: 0.3, ease: "power2.inOut" });
				}

				var siblings = document.querySelectorAll(".header .mainMenu .item");
				siblings.forEach(function (sibling) {
					if (sibling !== parentItem) {
						sibling.classList.remove("on");
						var siblingSubMenu = sibling.querySelector(".subMenu");
						if (siblingSubMenu) {
							siblingSubMenu.classList.remove("active");
						}
					}
				});
			});
		});
	}
}

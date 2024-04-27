import setMaskHeight from "./setProductMaskHeight";
import showVideo from "./showVideo";
import initYM from "./serviceMap";
import openFaqPopup from "./openFaqPopup";
import openAccordion from "./accordion";

export function createLoader() {
	const sidebarRight = document.querySelectorAll(".sidebar-right");
	sidebarRight.forEach((sidebar) => {
		const loader = document.createElement("span");
		loader.classList.add("loader");
		sidebar.insertAdjacentElement("afterbegin", loader);
	});
}

export function removeLoader() {
	const sidebarRight = document.querySelectorAll(".sidebar-right");

	sidebarRight.forEach((sidebar) => {
		if (sidebar.querySelector(".loader")) {
			sidebar?.querySelector(".loader").remove();
		}
	});
}

export default function openSelect() {
	let globalPagesNumber = 0;

	// Глобально вызванная формдата для отправки на бэк и запоминания значений
	const data = new FormData();

	async function renderItemsWithDelay(itemsList, button) {
		// Добавляем класс "loading" для кнопки, чтобы показать, что данные загружаются
		button.classList.add("loading");

		// Ожидаем небольшую задержку (например, 300 мс)
		await new Promise((resolve) => setTimeout(resolve, 300));

		// Удаляем класс "loading" после задержки
		button.classList.remove("loading");

		// Устанавливаем стиль "block" для itemsList
		itemsList.style.display = "block";
	}

	function updatePaginationCounterVideo(page, totalPages) {
		document.querySelector(".news-pagination__counter").textContent =
			page + "/" + totalPages;
	}

	let isActiveParent = false;
	let isActiveChild = false;

	const previousVideoParentProxy = new Proxy(
		{ value: document.querySelector(".video-parent")?.textContent },
		{
			set: function (target, key, value) {
				if (target.value !== value) {
					const childListContainer = document.querySelector(
						".video-search__select-list.series"
					);
					const childListContainerModel = document.querySelector(
						".video-search__select-list.model"
					);
					childListContainer.innerHTML = "";
					childListContainerModel.innerHTML = "";
					document.querySelector(".series-parent").textContent = "Серия";
					document.querySelector(".model-parent").textContent = "Модель";
					isActiveParent = true;
					target.value = value;
					if (document.querySelector(".popular-queries")) {
						document.querySelector(".popular-queries").style.display = "none";
					}
					if (document.querySelector(".sidebar-right__manual.manual")) {
						document.querySelector(
							".sidebar-right__manual.manual"
						).style.display = "block";
					}

					if (document.querySelector(".sidebar-right__oc")) {
						document.querySelector(".sidebar-right__oc").style.display =
							"block";
					}
				}
				return true;
			},
		}
	);

	const seriesParentProxy = new Proxy(
		{ value: document.querySelector(".series-parent")?.textContent },
		{
			set: function (target, key, value) {
				if (target.value !== value) {
					document.querySelector(".model-parent").textContent = "Модель";
					if (
						document.querySelector(".video-search__select-title.version-select")
					) {
						document.querySelector(
							".video-search__select-title.version-select"
						).textContent = "Версия";
					}

					if (document.querySelector(".video-search__select-title.op")) {
						document.querySelector(
							".video-search__select-title.op"
						).textContent = "Операционная система";
					}

					isActiveChild = true;
					target.value = value;
				}
				return true;
			},
		}
	);

	// function for filter
	const sendSelected = async (selectedParent, selectedChild) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		data.append("action", "load_selected");
		data.append("selectedParent", selectedParent);
		if (selectedChild) {
			data.append("selectedChild", selectedChild);
		}
		data.append(
			"linkUrlOperation",
			window.location.href.includes("operation") ||
			window.location.href.includes("consumables") ||
			window.location.href.includes("options-and-supplies")
		);
		data.append(
			"linkUrlDrive",
			window.location.href.includes("manual") ||
			window.location.href.includes("update") ||
			window.location.href.includes("faq") ||
			window.location.href.includes("driver") ||
			window.location.href.includes("service-centers")
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				if (
					window.location.href.includes("operation") ||
					window.location.href.includes("consumables") ||
					window.location.href.includes("options-and-supplies")
				) {
					const html = await response.text();
					const tempDiv = document.createElement("div");
					tempDiv.innerHTML = html.trim();
					const childListContainer = document.querySelector(
						".video-search__select-list.series"
					);
					const childListContainerModel = document.querySelector(
						".video-search__select-list.model"
					);

					if (childListContainer) {
						childListContainer.innerHTML = "";
						const childElements = tempDiv.querySelectorAll(
							".video-search__select-item.series"
						);
						childElements.forEach((childElement) =>
							childListContainer.appendChild(childElement)
						);

						if (childListContainerModel && selectedChild !== "Серия") {
							childListContainerModel.innerHTML = "";
							const childElementsModel = tempDiv.querySelectorAll(
								".video-search__select-item.model"
							);
							childElementsModel.forEach((childElement) =>
								childListContainerModel.appendChild(childElement)
							);
						}
					}
				} else if (
					window.location.href.includes("manual") ||
					window.location.href.includes("update") ||
					window.location.href.includes("faq") ||
					window.location.href.includes("driver") ||
					window.location.href.includes("service-centers")
				) {
					const dataFetch = await response.json();
					if (dataFetch.success) {
						const html = dataFetch.data.outputFilterMore;
						const tempDiv = document.createElement("div");
						tempDiv.innerHTML = html.trim();
						const childListContainer = document.querySelector(
							".video-search__select-list.series"
						);
						const childListContainerModel = document.querySelector(
							".video-search__select-list.model"
						);

						if (childListContainer) {
							childListContainer.innerHTML = "";
							const childElements = tempDiv.querySelectorAll(
								".video-search__select-item.series"
							);
							childElements.forEach((childElement) =>
								childListContainer.appendChild(childElement)
							);

							if (childListContainerModel && selectedChild !== "Серия") {
								childListContainerModel.innerHTML = "";
								const childElementsModel = tempDiv.querySelectorAll(
									".video-search__select-item.model"
								);
								childElementsModel.forEach((childElement) =>
									childListContainerModel.appendChild(childElement)
								);
							}
						}
						document.querySelectorAll(".ymaps-2-1-79-map").forEach((el) => {
							el.remove();
						});
						setTimeout(() => {
							initYM();
						}, 1000);
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
		showVideo();
	};

	// Service for filter
	const sendSelectedServiceFilter = async (selectedParent) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		data.append("action", "load_selected_service");
		if (selectedParent && selectedParent !== "Выберите регион") {
			data.append("selectedParentService", selectedParent);
		}

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					const html = dataFetch.data.outputFilterService;
					const tempDiv = document.createElement("div");
					tempDiv.innerHTML = html.trim();
					const childListContainer = document.querySelector(
						".video-search__select-list.gorod"
					);

					childListContainer.innerHTML = "";
					const childElements = tempDiv.querySelectorAll(
						".video-search__select-item.gorod"
					);

					childElements.forEach((childElement) =>
						childListContainer.appendChild(childElement)
					);
					document.querySelectorAll(".ymaps-2-1-79-map").forEach((el) => {
						el.remove();
					});
					setTimeout(() => {
						initYM();
					}, 1000);
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	const sendSelectedSidebar = async (
		changeSidebar,
		changeSeries,
		changeModel
	) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_video");
		if (changeSidebar !== "" && changeSidebar !== null) {
			data.append("changeSidebar", changeSidebar);
		} else if (changeSeries) {
			data.append("changeSeries", changeSeries);
		}
		if (changeModel && changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}

		const container = document.querySelector(".sidebar-right__list");
		const containerPagination = document.querySelector(
			".news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					container.innerHTML = dataFetch.data.html;
					if (containerPagination) {
						containerPagination.innerHTML = dataFetch.data.htmlPagination;

						setTimeout(() => {
							paginationReset();
						}, "300");
						if (dataFetch.data.has_more_posts) {
							document.querySelector(".news-pagination").style.display = "flex";
							updatePaginationCounterVideo(1, dataFetch.data.total_pages);
							globalPagesNumber = dataFetch.data.total_pages;
						} else {
							document.querySelector(".news-pagination").style.display = "none";
						}
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
		showVideo();
	};

	const selectButtons = document?.querySelectorAll(
		".video-search__select-title"
	);

	if (selectButtons) {
		selectButtons.forEach((button, index) => {
			button.addEventListener("click", async () => {
				button.classList.toggle("active");
				let videoLibr = document.querySelector(".video-parent");
				let seriesParent = document.querySelector(".series-parent");
				let modelParent = document.querySelector(".model-parent");

				let regionParent = document.querySelector(".region-parent");
				let gorodParent = document.querySelector(".gorod-parent");

				if (!isActiveParent) {
					seriesParent?.classList.remove("active");
					modelParent?.classList.remove("active");
				} else if (
					(!isActiveChild && seriesParent.textContent === "Серия") ||
					seriesParent.textContent === "Серия"
				) {
					modelParent?.classList.remove("active");
				}

				const field = button.closest(".video-search__select-field");
				const itemsList = field.querySelector(".video-search__select-list");

				// Показываем itemsList только если кнопка активна
				if (button.classList.contains("active")) {
					await renderItemsWithDelay(itemsList, button);
				} else {
					// Если кнопка неактивна, просто скрываем itemsList без задержки
					itemsList.style.display = "none";
				}

				const items = itemsList.querySelectorAll(".video-search__select-item");

				items.forEach((item) => {
					if (item) {
						item.addEventListener("click", async () => {
							const selectedItem = itemsList.querySelector(
								".video-search__select-item.selected"
							);

							if (selectedItem !== item) {
								selectedItem?.classList.remove("selected");
							}

							button.textContent = item.textContent;
							itemsList.style.display = "none";
							button.classList.remove("active");
							item.classList.add("selected");

							previousVideoParentProxy.value = videoLibr.textContent;
							seriesParentProxy.value = seriesParent.textContent;

							await sendSelected(
								videoLibr.textContent,
								seriesParent.textContent
							);

							if (window.location.href.includes("operation")) {
								await sendSelectedSidebar(
									"",
									seriesParent.textContent,
									modelParent.textContent
								);
							} else if (
								window.location.href.includes("consumables") ||
								window.location.href.includes("options-and-supplies")
							) {
								await sendSelectedSidebarOpcii(
									seriesParent.textContent,
									modelParent.textContent
								);
							} else if (window.location.href.includes("manual")) {
								sendSelectedManual(
									seriesParent.textContent,
									modelParent.textContent
								);
							} else if (window.location.href.includes("update")) {
								sendSelectedUpdate(
									seriesParent.textContent,
									modelParent.textContent
								);
							} else if (window.location.href.includes("faq")) {
								sendSelectedFaq(
									seriesParent.textContent,
									modelParent.textContent
								);
							} else if (window.location.href.includes("service-centers")) {
								sendSelectedService(
									seriesParent.textContent,
									modelParent.textContent,
									regionParent.textContent,
									gorodParent.textContent
								);

								sendSelectedServiceFilter(regionParent.textContent);
							}
						});
					}
				});
			});
		});
	}

	// Значения слева у сайдбара (видеотека)
	var items = document.querySelectorAll(".sidebar-left__inner-item");

	items.forEach(function (item) {
		item.addEventListener("click", function () {
			// Удаляем класс у всех элементов
			items.forEach(function (item) {
				item
					.querySelector(".sidebar-left__inner-link")
					.classList.remove("sidebar-left__inner-link--current");
				item
					.querySelector(".sidebar-left__inner-link")
					.classList.remove("videolib__inner-link--current");
			});

			// Добавляем класс только к текущему элементу
			this.querySelector(".sidebar-left__inner-link").classList.add(
				"sidebar-left__inner-link--current"
			);
			this.querySelector(".sidebar-left__inner-link").classList.add(
				"videolib__inner-link--current"
			);

			let changeSidebar = this.querySelector(
				".sidebar-left__inner-link--current"
			).textContent;

			if (window.location.href.includes("operation")) {
				sendSelectedSidebar(changeSidebar, "", "");
			}
		});
	});

	//Флаг для вызова фильтров при переходе по ссылкам 1 раз

	// Код для продуктов
	const sendSelectedSidebarProd = async () => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let urlParams = new URLSearchParams(window.location.search);
		let printerType = urlParams.getAll("type");
		var currentPageUrl = window.location.href;

		data.append("action", "load_sidebar_product");
		data.append("printer_type", printerType);
		data.append("currentPageUrl", currentPageUrl);
		const container = document.querySelector(".consumables__list.laser");
		const containerPagination = document.querySelector(
			".product .news-pagination__list"
		);
		const pagePagination = document.querySelector(".news-pagination.product");

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				createLoader();
				if (dataFetch.success) {
					setTimeout(() => {
						if (container) {
							container.innerHTML = dataFetch.data.outputProducts;
						}

						if (containerPagination) {
							containerPagination.innerHTML = dataFetch.data.htmlPaginationProd;
						}
						removeLoader();
						setMaskHeight();
						paginationReset();
					}, "300");

					if (
						dataFetch.data.has_more_posts_product &&
						pagePagination &&
						!window.location.href.includes("rezultaty-poiska")
					) {
						pagePagination.style.display = "flex";
						updatePaginationCounterVideo(
							1,
							dataFetch.data.total_pages_products
						);
						globalPagesNumber = dataFetch.data.total_pages_products;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	if (window.location.href.includes("laser-devices")) {
		sendSelectedSidebarProd();
	}

	// Код для опции и расходные материалы
	const sendSelectedSidebarOpcii = async (changeSeries, changeModel) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let urlParams = new URLSearchParams(window.location.search);
		let printerType = urlParams.getAll("type");
		var currentPageUrl = window.location.href;

		data.append("action", "load_sidebar_opcii");
		data.append("opcii_type", printerType);
		data.append("currentPageUrl", currentPageUrl);
		if (changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}
		data.append("changeSeries", changeSeries);
		const container = document?.querySelector(".consumables__list.opcii");
		const containerPagination = document?.querySelector(
			".opcii .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				createLoader();
				if (dataFetch.success) {
					setTimeout(() => {
						if (container) {
							container.innerHTML = dataFetch.data.outputOpcii;
						}

						if (containerPagination) {
							containerPagination.innerHTML =
								dataFetch.data.htmlPaginationOpcii;
						}
						removeLoader();
						setMaskHeight();
						paginationReset();
					}, "300");

					if (
						dataFetch.data.has_more_posts_opcii &&
						container?.children.length >= 7 &&
						!window.location.href.includes("rezultaty-poiska")
					) {
						document.querySelector(".news-pagination.opcii").style.display =
							"flex";
						updatePaginationCounterVideo(1, dataFetch.data.total_pages_opcii);
						globalPagesNumber = dataFetch.data.total_pages_opcii;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	if (
		window.location.href.includes("consumables") ||
		window.location.href.includes("options-and-supplies")
	) {
		sendSelectedSidebarOpcii("", "");
	}

	// function setFilterTemplate() {
	// 	if (window.location.href.includes("laser-devices")) {
	// 		let urlParams = new URLSearchParams(window.location.search);
	// 		let printerType = urlParams.getAll("type").join(",").toLowerCase();
	// 		const productSidebar = document?.querySelector(
	// 			".sidebar-left__item--product"
	// 		);

	// 		const activeTitle = productSidebar?.querySelector(".laser");
	// 		const content = productSidebar?.querySelector(".accordion__content");

	// 		//Чекбоксы фильтров
	// 		const mono = productSidebar
	// 			.querySelector(".accordion-inner")
	// 			.querySelector(".consumables-menu__inner-item:nth-child(2)");

	// 		const color = productSidebar
	// 			.querySelector(".accordion-inner")
	// 			.querySelector(".consumables-menu__inner-item:nth-child(1)");

	// 		const printer = productSidebar
	// 			.querySelector(".accordion-inner:nth-child(2)")
	// 			.querySelector(".consumables-menu__inner-item");

	// 		const mfu = productSidebar
	// 			.querySelector(".accordion-inner:nth-child(2)")
	// 			.querySelector(".consumables-menu__inner-item:nth-child(2)");

	// 		if (printerType.length > 0) {
	// 			productSidebar.classList.add("open");
	// 			activeTitle.classList.add("sidebar-left__title--current");
	// 			content.style.display = "block";
	// 			content.style.height = "auto";
	// 		}

	// 		const innerAccordion =
	// 			productSidebar.querySelectorAll(".accordion-inner");

	// 		for (let i = 0; i <= 1; i++) {
	// 			innerAccordion[i].classList.add("active");
	// 			innerAccordion[i].querySelector(
	// 				".accordion-inner__content"
	// 			).style.maxHeight = content.scrollHeight + "px";
	// 		}

	// 		const listItemColor = document.createElement("li");
	// 		const listItemType = document.createElement("li");

	// 		switch (printerType) {
	// 			case "monohromnaya,printer":
	// 				mono.classList.add("active");
	// 				printer.classList.add("active");

	// 				selectList.style.display = "flex";

	// 				listItemColor.classList.add("sidebar-right__select-item");
	// 				listItemColor.innerHTML = `<a class="sidebar-right__select-link">${mono.textContent}</a>`;

	// 				listItemType.classList.add("sidebar-right__select-item");
	// 				listItemType.innerHTML = `<a class="sidebar-right__select-link">${printer.textContent}</a>`;
	// 				selectList.insertAdjacentElement("afterbegin", listItemType);
	// 				selectList.insertAdjacentElement("afterbegin", listItemColor);

	// 				break;
	// 			case "tsvetnaya,printer":
	// 				color.classList.add("active");
	// 				printer.classList.add("active");

	// 				selectList.style.display = "flex";

	// 				listItemColor.classList.add("sidebar-right__select-item");
	// 				listItemColor.innerHTML = `<a class="sidebar-right__select-link">${color.textContent}</a>`;

	// 				listItemType.classList.add("sidebar-right__select-item");
	// 				listItemType.innerHTML = `<a class="sidebar-right__select-link">${printer.textContent}</a>`;

	// 				selectList.insertAdjacentElement("afterbegin", listItemType);
	// 				selectList.insertAdjacentElement("afterbegin", listItemColor);
	// 				break;
	// 			case "monohromnaya,mfu":
	// 				mono.classList.add("active");
	// 				mfu.classList.add("active");

	// 				selectList.style.display = "flex";

	// 				listItemColor.classList.add("sidebar-right__select-item");
	// 				listItemColor.innerHTML = `<a class="sidebar-right__select-link">${mono.textContent}</a>`;

	// 				listItemType.classList.add("sidebar-right__select-item");
	// 				listItemType.innerHTML = `<a class="sidebar-right__select-link">${mfu.textContent}</a>`;
	// 				selectList.insertAdjacentElement("afterbegin", listItemType);
	// 				selectList.insertAdjacentElement("afterbegin", listItemColor);
	// 				break;
	// 			case "tsvetnaya,mfu":
	// 				color.classList.add("active");
	// 				mfu.classList.add("active");

	// 				selectList.style.display = "flex";
	// 				listItemColor.classList.add("sidebar-right__select-item");
	// 				listItemColor.innerHTML = `<a class="sidebar-right__select-link">${color.textContent}</a>`;

	// 				listItemType.classList.add("sidebar-right__select-item");
	// 				listItemType.innerHTML = `<a class="sidebar-right__select-link">${mfu.textContent}</a>`;
	// 				selectList.insertAdjacentElement("afterbegin", listItemType);
	// 				selectList.insertAdjacentElement("afterbegin", listItemColor);
	// 				break;
	// 		}
	// 	}
	// }

	// window.addEventListener("load", () => {});

	// Руководство пользователя
	const sendSelectedManual = async (changeSeries, changeModel) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_manual");
		if (changeModel && changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}
		data.append("changeSeries", changeSeries);

		const container = document.querySelector(".sidebar-right__manual.manual");

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				createLoader();
				const dataFetch = await response.json();
				if (dataFetch.success) {
					setTimeout(() => {
						container.innerHTML = dataFetch.data.outputManual;
						removeLoader();
					}, "300");
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// Код для прошивок
	const sendSelectedUpdate = async (changeSeries, changeModel) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_update");
		if (changeModel && changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}
		data.append("changeSeries", changeSeries);

		const container = document.querySelector(".sidebar-right__manual.firmware");

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				createLoader();
				const dataFetch = await response.json();
				if (dataFetch.success) {
					setTimeout(() => {
						container.innerHTML = dataFetch.data.outputManual;
						removeLoader();
					}, "300");
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// Код для FAQ
	const sendSelectedFaq = async (changeSeries, changeModel) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_faq");
		if (changeModel && changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}
		data.append("changeSeries", changeSeries);

		const container = document.querySelector(".faq-container");

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				createLoader();
				const dataFetch = await response.json();
				if (dataFetch.success) {
					setTimeout(() => {
						container.innerHTML = dataFetch.data.outputFaq;
						openFaqPopup();
						removeLoader();
					}, "300");
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// Код для Serice
	const sendSelectedService = async (
		changeSeries,
		changeModel,
		region,
		gorod
	) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_serivce");
		if (changeModel && changeModel !== "Модель") {
			data.append("changeModel", changeModel);
		}
		if (changeSeries && changeSeries !== "Серия") {
			data.append("changeSeries", changeSeries);
		}

		if (region && region !== "Выберите регион") {
			data.append("changeRegion", region);
		}

		if (gorod && gorod !== "Выберите город") {
			data.append("changeGorod", gorod);
		}

		const container = document.querySelector(".service-center__list");

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				createLoader();
				const dataFetch = await response.json();
				if (dataFetch.success) {
					setTimeout(() => {
						container.innerHTML = dataFetch.data.outputService;
						removeLoader();
					}, "300");
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// SetActiveCheckbox

	const checkboxItems = document?.querySelectorAll(
		".consumables-menu__inner-item"
	);
	const selectList = document?.querySelector(".sidebar-right__select-list");
	const clearListButton = selectList?.querySelector(
		".sidebar-right__select-link--clear"
	);
	let currentTypes = [];

	checkboxItems.forEach((checkbox) => {
		checkbox.addEventListener("click", (evt) => {
			setTimeout(() => {
				if (window.location.href.includes("laser-devices")) {
					sendSelectedSidebarProd("", "");
				} else if (window.location.href.includes("options-and-supplies")) {
					sendSelectedSidebarOpcii("", "");
				}
			}, 300);
			checkbox.classList.toggle("active");

			if (checkbox.classList.contains("active") && selectList.length > 0) {
				console.log(111);
			}
			// Очистка текста от лишних пробелов и символов новой строки
			const checkboxText = checkbox.textContent.trim();

			if (checkbox.classList.contains("active")) {
				currentTypes.push(checkboxText);
			} else {
				const index = currentTypes.indexOf(checkboxText);
				if (index !== -1) {
					currentTypes.splice(index, 1);
				}
			}

			updateUrl(); // Обновление URL после изменения

			if (checkbox.classList.contains("active")) {
				selectList.style.display = "flex";

				const listItem = document.createElement("li");
				listItem.classList.add("sidebar-right__select-item");
				listItem.innerHTML = `<a class="sidebar-right__select-link">${checkboxText}</a>`;
				selectList.insertAdjacentElement("afterbegin", listItem);

				listItem.addEventListener("click", () => {
					const index = currentTypes.indexOf(checkboxText);
					setTimeout(() => {
						if (window.location.href.includes("laser-devices")) {
							sendSelectedSidebarProd("", "");
						} else if (window.location.href.includes("options-and-supplies")) {
							sendSelectedSidebarOpcii("", "");
						}
					}, 300);

					if (index !== -1) {
						currentTypes.splice(index, 1);
					}
					listItem.remove();
					updateUrl(); // Обновление URL после изменения
					if (
						selectList.querySelectorAll(".sidebar-right__select-item")
							.length === 0
					) {
						selectList.style.display = "none";
						clearListButton.style.display = "none";
					}
					checkbox.classList.remove("active");
				});

				clearListButton.style.display = "block";
			} else {
				//Ищем все добавленные модификаторы поиска
				const selectedItems = selectList?.querySelectorAll(
					".sidebar-right__select-item"
				);
				//Если текст модификатора идентичен чекбоксу, то при его деактивации элемент с выбранным модификатором удаляется
				selectedItems.forEach((item) => {
					item.addEventListener("click", () => {
						if (item.textContent == checkboxText) {
							checkbox.classList.remove("active");
							item.remove();
						}
					});
					if (item.textContent == checkboxText) {
						item.remove();
					}
				});

				if (
					selectList.querySelectorAll(".sidebar-right__select-item").length ===
					0
				) {
					selectList.style.display = "none";
					clearListButton.style.display = "none";
				}
				checkbox.classList.remove("active");
				updateUrl();
			}
		});
	});

	const clearSelectedItems = () => {
		currentTypes = [];
		selectList
			.querySelectorAll(".sidebar-right__select-item")
			.forEach((item) => item.remove());
		selectList.style.display = "none";
		checkboxItems.forEach((checkbox) => checkbox.classList.remove("active"));
		clearListButton.style.display = "none";
		updateUrl(); // Обновление URL после изменения
		setTimeout(() => {
			if (window.location.href.includes("laser-devices")) {
				sendSelectedSidebarProd("", "");
			} else if (window.location.href.includes("options-and-supplies")) {
				sendSelectedSidebarOpcii("", "");
			}
		}, 300);
	};

	clearListButton?.addEventListener("click", clearSelectedItems);

	var searchProd = document?.getElementById("searchVal");
	const searchResultButton = document.querySelector(".sidebar__search-button");
	searchResultButton?.addEventListener("click", (evt) => {
		evt.preventDefault();
		clearSelectedItems();
		if (window.location.href.includes("consumables")) {
			OpciiSearch(searchProd.value);
		} else if (
			window.location.href.includes("products") ||
			window.location.href.includes("laser-devices")
		) {
			ProductSearch(searchProd.value);
		}
	});

	// Функция транслитерации в JavaScript
	function transliterate(text) {
		var cyrillic =
			"абвгдеёжзийклмнопрстуфхцчшщъыьэюя" + "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
		var latin = [
			"a",
			"b",
			"v",
			"g",
			"d",
			"e",
			"yo",
			"zh",
			"z",
			"i",
			"y",
			"k",
			"l",
			"m",
			"n",
			"o",
			"p",
			"r",
			"s",
			"t",
			"u",
			"f",
			"h",
			"ts",
			"ch",
			"sh",
			"shch",
			"",
			"y",
			"",
			"e",
			"yu",
			"ya",
			"A",
			"B",
			"V",
			"G",
			"D",
			"E",
			"Yo",
			"Zh",
			"Z",
			"I",
			"Y",
			"K",
			"L",
			"M",
			"N",
			"O",
			"P",
			"R",
			"S",
			"T",
			"U",
			"F",
			"H",
			"Ts",
			"Ch",
			"Sh",
			"Shch",
			"",
			"Y",
			"",
			"E",
			"Yu",
			"Ya",
		];
		var result = "";
		for (var i = 0; i < text.length; i++) {
			var index = cyrillic.indexOf(text[i]);
			if (index !== -1) {
				result += latin[index];
			} else {
				result += text[i];
			}
		}

		return result.toLowerCase().replace(/\s+/g, ""); // Удаляем пробелы и приводим к нижнему регистру
	}

	// Обновление URL с применением транслитерации и удалением пробелов
	function updateUrl() {
		const urlParams = new URLSearchParams(window.location.search);
		if (currentTypes.length > 0) {
			const transliteratedTypes = currentTypes
				.map((type) => transliterate(type))
				.join(",");
			urlParams.set("type", transliteratedTypes);
		} else {
			urlParams.delete("type");
		}

		const newUrl =
			window.location.origin +
			window.location.pathname +
			"?" +
			urlParams.toString();
		history.replaceState(null, null, newUrl);
	}

	// Тут начинается код пагинации

	function paginationReset() {
		var paginationContainer;
		setMaskHeight();
		if (window.location.href.includes("rezultaty-poiska")) {
			document
				.querySelectorAll(".sidebar__right.sidebar-right")
				.forEach((item) => {
					if (item.style.display !== "none") {
						const newsPaginationList = item.querySelector(
							".news-pagination__list"
						);
						if (newsPaginationList) {
							paginationContainer = newsPaginationList.parentNode;
						}
					}
				});
		} else {
			paginationContainer = document.querySelector(".news-pagination__list");
		}

		var paginationLinks = paginationContainer?.querySelectorAll(
			".news-pagination__link:not(.pagination-link--next):not(.pagination-link--prev)"
		);
		var prevLink = document.querySelector(".pagination-link--prev");
		var nextLink = document.querySelector(".pagination-link--next");
		var currentPageIndex = 0;

		if (paginationContainer) {
			paginationLinks[currentPageIndex]?.classList.add("current-pagination");
			updatePrevNextVisibility();

			paginationLinks.forEach(function (link, index) {
				link.addEventListener("click", function (e) {
					e.preventDefault();
					setTimeout(() => {
						setMaskHeight();
					}, 1000);
					paginationLinks.forEach(function (link) {
						link.classList.remove("current-pagination");
					});
					this.classList.add("current-pagination");

					currentPageIndex = index;
					updatePrevNextVisibility();
					const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
					var page = this.textContent || this.innerText;

					var xhr_news = new XMLHttpRequest();
					xhr_news.onreadystatechange = function () {
						if (document.querySelector(".newsboard__list")) {
							if (xhr_news.readyState === 4 && xhr_news.status === 200) {
								document.querySelector(".newsboard__list").innerHTML =
									xhr_news.responseText;
								updatePaginationCounter(page);
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_news.open("POST", ajaxurl, true);
					xhr_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".newsboard__list")) {
						xhr_news.send("action=load_more_posts&page=" + page);
					}

					var xhr_video = new XMLHttpRequest();
					xhr_video.onreadystatechange = function () {
						if (document.querySelector(".sidebar-right__list.video")) {
							if (xhr_video.readyState === 4 && xhr_video.status === 200) {
								var response = JSON.parse(xhr_video.responseText);
								if (response.success && response.data.has_more_posts) {
									document.querySelector(
										".sidebar-right__list.video"
									).innerHTML = response.data.html;
									document.querySelector(".news-pagination").style.display =
										"flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								if (response.data.has_more_posts) {
									document.querySelector(".news-pagination").style.display =
										"flex";
								} else {
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_video.open("POST", ajaxurl, true);
					xhr_video.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".sidebar-right__list.video")) {
						xhr_video.send("action=load_sidebar_video&page=" + page);
					}
					// TUT NACHAL
					var xhr_product = new XMLHttpRequest();
					xhr_product.onreadystatechange = function () {
						if (document.querySelector(".consumables__list.laser")) {
							if (xhr_product.readyState === 4 && xhr_product.status === 200) {
								var response = JSON.parse(xhr_product.responseText);
								if (response.success && response.data.has_more_posts_product) {
									document.querySelector(".consumables__list.laser").innerHTML =
										response.data.outputProducts;
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_products
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
								} else {
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_product.open("POST", ajaxurl, true);
					xhr_product.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_product.send("action=load_sidebar_product&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_opcii = new XMLHttpRequest();
					xhr_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (xhr_opcii.readyState === 4 && xhr_opcii.status === 200) {
								var response = JSON.parse(xhr_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_opcii) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_opcii.open("POST", ajaxurl, true);
					xhr_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (
						document.querySelector(".consumables__list.opcii").style.display !==
						"none"
					) {
						xhr_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC
					// // TUT NACHAL
					var xhr_search_drive = new XMLHttpRequest();
					xhr_search_drive.onreadystatechange = function () {
						if (document.querySelector(".driver-container")) {
							if (
								xhr_search_drive.readyState === 4 &&
								xhr_search_drive.status === 200
							) {
								var response = JSON.parse(xhr_search_drive.responseText);
								if (response.success && response.data.has_more_posts_drive) {
									document.querySelector(".driver-container").innerHTML =
										response.data.outputDrive;
									document.querySelector(
										".news-pagination.drive"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_drive
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_drive) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_drive.open("POST", ajaxurl, true);
					xhr_search_drive.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_drive.send("action=load_sidebar_drive&page=" + page);
					}
					// // KONEC
					// // TUT NACHAL
					var xhr_search_manual = new XMLHttpRequest();
					xhr_search_manual.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.manual")) {
							if (
								xhr_search_manual.readyState === 4 &&
								xhr_search_manual.status === 200
							) {
								var response = JSON.parse(xhr_search_manual.responseText);
								if (response.success && response.data.has_more_posts_manual) {
									document.querySelector(
										".search-page__news-list.manual"
									).innerHTML = response.data.outputManual;
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_manual
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.manual"
									).style.display = "none";
								}
								if (response.data.has_more_posts_manual) {
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_manual.open("POST", ajaxurl, true);
					xhr_search_manual.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_manual.send("action=load_sidebar_manualS&page=" + page);
					}
					// // KONEC
					// TUT NACHAL
					var xhr_search_news = new XMLHttpRequest();
					xhr_search_news.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.news")) {
							if (
								xhr_search_news.readyState === 4 &&
								xhr_search_news.status === 200
							) {
								var response = JSON.parse(xhr_search_news.responseText);
								if (response.success && response.data.has_more_posts_news) {
									document.querySelector(
										".search-page__news-list.news"
									).innerHTML = response.data.outputNews;
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_news
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_news.open("POST", ajaxurl, true);
					xhr_search_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_news.send("action=load_sidebar_news&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_search_opcii = new XMLHttpRequest();
					xhr_search_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (
								xhr_search_opcii.readyState === 4 &&
								xhr_search_opcii.status === 200
							) {
								var response = JSON.parse(xhr_search_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_opcii.open("POST", ajaxurl, true);
					xhr_search_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC
				});
			});

			prevLink?.addEventListener("click", function (e) {
				e.preventDefault();
				setTimeout(() => {
					setMaskHeight();
				}, 1000);
				if (currentPageIndex > 0) {
					paginationLinks[currentPageIndex].classList.remove(
						"current-pagination"
					);
					currentPageIndex--;
					paginationLinks[currentPageIndex].classList.add("current-pagination");
					updatePrevNextVisibility();
					const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
					var page =
						paginationLinks[currentPageIndex].textContent ||
						paginationLinks[currentPageIndex].innerText;

					var xhr_news = new XMLHttpRequest();
					xhr_news.onreadystatechange = function () {
						if (document.querySelector(".newsboard__list")) {
							if (xhr_news.readyState === 4 && xhr_news.status === 200) {
								document.querySelector(".newsboard__list").innerHTML =
									xhr_news.responseText;
								updatePaginationCounter(page);
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_news.open("POST", ajaxurl, true);
					xhr_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".newsboard__list")) {
						xhr_news.send("action=load_more_posts&page=" + page);
					}

					var xhr_video = new XMLHttpRequest();
					xhr_video.onreadystatechange = function () {
						if (document.querySelector(".sidebar-right__list.video")) {
							if (xhr_video.readyState === 4 && xhr_video.status === 200) {
								var response = JSON.parse(xhr_video.responseText);
								if (response.success && response.data.has_more_posts) {
									document.querySelector(
										".sidebar-right__list.video"
									).innerHTML = response.data.html;
									document.querySelector(".news-pagination").style.display =
										"flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								if (response.data.has_more_posts) {
									document.querySelector(".news-pagination").style.display =
										"flex";
								} else {
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_video.open("POST", ajaxurl, true);
					xhr_video.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".sidebar-right__list.video")) {
						xhr_video.send("action=load_sidebar_video&page=" + page);
					}

					// // TUT NACHAL
					var xhr_search_drive = new XMLHttpRequest();
					xhr_search_drive.onreadystatechange = function () {
						if (document.querySelector(".driver-container")) {
							if (
								xhr_search_drive.readyState === 4 &&
								xhr_search_drive.status === 200
							) {
								var response = JSON.parse(xhr_search_drive.responseText);
								if (response.success && response.data.has_more_posts_drive) {
									document.querySelector(".driver-container").innerHTML =
										response.data.outputDrive;
									document.querySelector(
										".news-pagination.drive"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_drive
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_drive) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_drive.open("POST", ajaxurl, true);
					xhr_search_drive.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_drive.send("action=load_sidebar_drive&page=" + page);
					}
					// // KONEC
					// // TUT NACHAL
					var xhr_search_manual = new XMLHttpRequest();
					xhr_search_manual.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.manual")) {
							if (
								xhr_search_manual.readyState === 4 &&
								xhr_search_manual.status === 200
							) {
								var response = JSON.parse(xhr_search_manual.responseText);
								if (response.success && response.data.has_more_posts_manual) {
									document.querySelector(
										".search-page__news-list.manual"
									).innerHTML = response.data.outputManual;
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_manual
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.manual"
									).style.display = "none";
								}
								if (response.data.has_more_posts_manual) {
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_manual.open("POST", ajaxurl, true);
					xhr_search_manual.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_manual.send("action=load_sidebar_manualS&page=" + page);
					}
					// // KONEC
					// TUT NACHAL
					var xhr_search_news = new XMLHttpRequest();
					xhr_search_news.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.news")) {
							if (
								xhr_search_news.readyState === 4 &&
								xhr_search_news.status === 200
							) {
								var response = JSON.parse(xhr_search_news.responseText);
								if (response.success && response.data.has_more_posts_news) {
									document.querySelector(
										".search-page__news-list.news"
									).innerHTML = response.data.outputNews;
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_news
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_news.open("POST", ajaxurl, true);
					xhr_search_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_news.send("action=load_sidebar_news&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_search_opcii = new XMLHttpRequest();
					xhr_search_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (
								xhr_search_opcii.readyState === 4 &&
								xhr_search_opcii.status === 200
							) {
								var response = JSON.parse(xhr_search_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_opcii.open("POST", ajaxurl, true);
					xhr_search_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC

					// TUT NACHAL
					var xhr_product = new XMLHttpRequest();
					xhr_product.onreadystatechange = function () {
						if (document.querySelector(".consumables__list.laser")) {
							if (xhr_product.readyState === 4 && xhr_product.status === 200) {
								var response = JSON.parse(xhr_product.responseText);
								if (response.success && response.data.has_more_posts_product) {
									document.querySelector(".consumables__list.laser").innerHTML =
										response.data.outputProducts;
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_products
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
								} else {
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_product.open("POST", ajaxurl, true);
					xhr_product.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_product.send("action=load_sidebar_product&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_opcii = new XMLHttpRequest();
					xhr_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (xhr_opcii.readyState === 4 && xhr_opcii.status === 200) {
								var response = JSON.parse(xhr_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_opcii) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_opcii.open("POST", ajaxurl, true);
					xhr_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (
						document.querySelector(".consumables__list.opcii").style.display !==
						"none"
					) {
						xhr_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC
				}
			});

			nextLink?.addEventListener("click", function (e) {
				e.preventDefault();
				setTimeout(() => {
					setMaskHeight();
				}, 1000);

				if (currentPageIndex < paginationLinks.length - 1) {
					paginationLinks[currentPageIndex].classList.remove(
						"current-pagination"
					);
					currentPageIndex++;
					paginationLinks[currentPageIndex].classList.add("current-pagination");
					updatePrevNextVisibility();
					const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

					var page =
						paginationLinks[currentPageIndex].textContent ||
						paginationLinks[currentPageIndex].innerText;

					var xhr_news = new XMLHttpRequest();
					xhr_news.onreadystatechange = function () {
						if (document.querySelector(".newsboard__list")) {
							if (xhr_news.readyState === 4 && xhr_news.status === 200) {
								document.querySelector(".newsboard__list").innerHTML =
									xhr_news.responseText;
								updatePaginationCounter(page);
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_news.open("POST", ajaxurl, true);
					xhr_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".newsboard__list")) {
						xhr_news.send("action=load_more_posts&page=" + page);
					}

					var xhr_video = new XMLHttpRequest();
					xhr_video.onreadystatechange = function () {
						if (document.querySelector(".sidebar-right__list.video")) {
							if (xhr_video.readyState === 4 && xhr_video.status === 200) {
								var response = JSON.parse(xhr_video.responseText);
								if (response.success && response.data.has_more_posts) {
									document.querySelector(
										".sidebar-right__list.video"
									).innerHTML = response.data.html;
									document.querySelector(".news-pagination").style.display =
										"flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								if (response.data.has_more_posts) {
									document.querySelector(".news-pagination").style.display =
										"flex";
								} else {
									document.querySelector(".news-pagination").style.display =
										"none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_video.open("POST", ajaxurl, true);
					xhr_video.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".sidebar-right__list.video")) {
						xhr_video.send("action=load_sidebar_video&page=" + page);
					}

					// // TUT NACHAL
					var xhr_search_drive = new XMLHttpRequest();
					xhr_search_drive.onreadystatechange = function () {
						if (document.querySelector(".driver-container")) {
							if (
								xhr_search_drive.readyState === 4 &&
								xhr_search_drive.status === 200
							) {
								var response = JSON.parse(xhr_search_drive.responseText);
								if (response.success && response.data.has_more_posts_drive) {
									document.querySelector(".driver-container").innerHTML =
										response.data.outputDrive;
									document.querySelector(
										".news-pagination.drive"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_drive
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_drive) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_drive.open("POST", ajaxurl, true);
					xhr_search_drive.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_drive.send("action=load_sidebar_drive&page=" + page);
					}
					// // KONEC
					// // TUT NACHAL
					var xhr_search_manual = new XMLHttpRequest();
					xhr_search_manual.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.manual")) {
							if (
								xhr_search_manual.readyState === 4 &&
								xhr_search_manual.status === 200
							) {
								var response = JSON.parse(xhr_search_manual.responseText);
								if (response.success && response.data.has_more_posts_manual) {
									document.querySelector(
										".search-page__news-list.manual"
									).innerHTML = response.data.outputManual;
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_manual
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.manual"
									).style.display = "none";
								}
								if (response.data.has_more_posts_manual) {
									document.querySelector(
										".news-pagination.manual"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_manual.open("POST", ajaxurl, true);
					xhr_search_manual.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_manual.send("action=load_sidebar_manualS&page=" + page);
					}
					// // KONEC
					// TUT NACHAL
					var xhr_search_news = new XMLHttpRequest();
					xhr_search_news.onreadystatechange = function () {
						if (document.querySelector(".search-page__news-list.news")) {
							if (
								xhr_search_news.readyState === 4 &&
								xhr_search_news.status === 200
							) {
								var response = JSON.parse(xhr_search_news.responseText);
								if (response.success && response.data.has_more_posts_news) {
									document.querySelector(
										".search-page__news-list.news"
									).innerHTML = response.data.outputNews;
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_news
											: globalPagesNumber
									);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.news"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.news"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_news.open("POST", ajaxurl, true);
					xhr_search_news.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_news.send("action=load_sidebar_news&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_search_opcii = new XMLHttpRequest();
					xhr_search_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (
								xhr_search_opcii.readyState === 4 &&
								xhr_search_opcii.status === 200
							) {
								var response = JSON.parse(xhr_search_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_search_opcii.open("POST", ajaxurl, true);
					xhr_search_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_search_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_product = new XMLHttpRequest();
					xhr_product.onreadystatechange = function () {
						if (document.querySelector(".consumables__list.laser")) {
							if (xhr_product.readyState === 4 && xhr_product.status === 200) {
								var response = JSON.parse(xhr_product.responseText);
								if (response.success && response.data.has_more_posts_product) {
									document.querySelector(".consumables__list.laser").innerHTML =
										response.data.outputProducts;
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_products
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								if (response.data.has_more_posts_product) {
									document.querySelector(
										".news-pagination.product"
									).style.display = "flex";
								} else {
									document.querySelector(
										".news-pagination.product"
									).style.display = "none";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_product.open("POST", ajaxurl, true);
					xhr_product.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (document.querySelector(".consumables__list")) {
						xhr_product.send("action=load_sidebar_product&page=" + page);
					}
					// KONEC
					// TUT NACHAL
					var xhr_opcii = new XMLHttpRequest();
					xhr_opcii.onreadystatechange = function () {
						if (
							document.querySelector(".consumables__list.opcii").style
								.display !== "none"
						) {
							if (xhr_opcii.readyState === 4 && xhr_opcii.status === 200) {
								var response = JSON.parse(xhr_opcii.responseText);
								if (response.success && response.data.has_more_posts_opcii) {
									document.querySelector(".consumables__list.opcii").innerHTML =
										response.data.outputOpcii;
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
									updatePaginationCounterVideo(
										page,
										globalPagesNumber === 0
											? response.data.total_pages_opcii
											: globalPagesNumber
									);
									setTimeout(() => {
										setMaskHeight();
									}, 300);
								} else {
									console.error("Ошибка при загрузке видео:", response);
								}
								if (response.data.has_more_posts_opcii) {
									document.querySelector(
										".news-pagination.opcii"
									).style.display = "flex";
								}
								var canonicalTag = document.querySelector(
									'link[rel="canonical"]'
								);
								canonicalTag.href = window.location.href;
							}
						}
					};
					xhr_opcii.open("POST", ajaxurl, true);
					xhr_opcii.setRequestHeader(
						"Content-Type",
						"application/x-www-form-urlencoded"
					);
					if (
						document.querySelector(".consumables__list.opcii").style.display !==
						"none"
					) {
						xhr_opcii.send("action=load_sidebar_opcii&page=" + page);
					}
					// KONEC
				}
			});

			updatePaginationCounter(1);

			function updatePaginationCounter(page) {
				var totalPages = parseInt(
					document
						.querySelector(".news-pagination__list")
						.querySelectorAll(
							".news-pagination__link:not(.pagination-link--next):not(.pagination-link--prev)"
						).length
				);
				document.querySelector(".news-pagination__counter").textContent =
					page + "/" + totalPages;
			}

			function updatePrevNextVisibility() {
				if (!prevLink || !nextLink) {
					return; // Выходим из функции, если prevLink или nextLink не определены
				}

				if (currentPageIndex === 0) {
					prevLink.style.display = "none";
				} else {
					prevLink.style.display = "inline-block";
				}

				if (currentPageIndex === paginationLinks.length - 1) {
					nextLink.style.display = "none";
				} else {
					nextLink.style.display = "inline-block";
				}

				const screenWidth = window.innerWidth;
				var visiblePages = screenWidth < 365 ? 3 : 4;
				var visiblePagesSecond = screenWidth < 365 ? 3 : 6;
				var startIndex = Math.max(0, currentPageIndex - visiblePages);
				var endIndex = Math.min(
					paginationLinks.length - 1,
					startIndex + visiblePagesSecond
				);

				paginationLinks.forEach(function (link) {
					link.parentNode.style.display = "none";
				});

				for (var i = startIndex; i <= endIndex; i++) {
					paginationLinks[i].parentNode.style.display = "block";
				}
			}
		}
		showVideo();
	}
	paginationReset();

	// Сброс до значений по умолчанию
	const resetButton = document?.querySelector(".device-search__reset");
	const type = document?.querySelector(".video-parent");
	const series = document?.querySelector(".series-parent");
	const model = document?.querySelector(".model-parent");
	const valueList = document?.querySelectorAll(".video-search__select-list");
	const resetSelectValue = () => {
		type.textContent = "Тип";
		series.textContent = "Серия";
		model.textContent = "Модель";

		sendSelectedSidebarOpcii("", "");

		valueList.forEach((el) => {
			const links = el?.querySelectorAll(".video-search__select-item");
			links.forEach((link) => {
				if (link.classList.contains("selected")) {
					link.classList.remove("selected");
				}
			});
		});
	};

	if (
		window.location.href.includes("consumables") ||
		window.location.href.includes("options-and-supplies")
	) {
		resetButton?.addEventListener("click", resetSelectValue);
	}

	// Подписаться на рассылку

	const form = document.querySelector(".subscribe__form");

	form?.addEventListener("submit", function (event) {
		event.preventDefault(); // Предотвращаем отправку формы по умолчанию

		// Получаем данные формы
		const formData = new FormData(form);

		// Отправляем данные на сервер с помощью AJAX
		fetch(window.location.origin + "/wp-admin/admin-ajax.php", {
			method: "POST",
			body: formData,
		})
			.then((response) => {
				if (!response.ok) {
					form.querySelector(".form-success-message").style.display = "block";
					setTimeout(() => {
						form.querySelector(".form-success-message").style.display = "none";
					}, 3000);
					throw new Error("Произошла ошибка при обработке запроса");
				}
				return response.text();
			})
			.then((data) => {
				console.log("Данные успешно отправлены", data);

				// Дополнительные действия после успешной отправки данных
			})
			.catch((error) => {
				console.error("Ошибка:", error);
				// Обработка ошибок при отправке данных
			});
	});

	// Обращение в Пантум

	const formRe = document.querySelector(".suggest-form");

	formRe?.addEventListener("submit", function (event) {
		event.preventDefault(); // Предотвращаем стандартную отправку формы

		// Получаем данные формы
		const formData = new FormData(formRe);

		// Добавляем дополнительные данные
		formData.append("action", "send_sug_action");

		// Проходимся по всем полям формы, чтобы добавить их значения
		const formInputs = formRe.querySelectorAll("input, textarea");
		formInputs.forEach((input) => {
			if (input.type !== "file") {
				// Исключаем файловые поля
				formData.append(input.name, input.value);
			} else {
				// Для файловых полей
				const files = input.files;
				console.log("Selected files:", files); // Добавляем отладочный вывод
				for (let i = 0; i < files.length; i++) {
					formData.append(`${input.name}_${i}`, files[i]); // Уникальное имя поля файла
				}
			}
		});

		// Отправляем данные через AJAX
		fetch(window.location.origin + "/wp-admin/admin-ajax.php", {
			method: "POST",
			body: formData,
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error("Произошла ошибка при обработке запроса");
				}
				return response.text();
			})
			.then((data) => {
				formRe.querySelector(".form-success-message").style.display = "block";
				setTimeout(() => {
					formRe.querySelector(".form-success-message").style.display = "none";
				}, 3000);
				console.log("Данные успешно отправлены", data);
				// Дополнительные действия после успешной отправки данных
			})
			.catch((error) => {
				console.error("Ошибка:", error);
				// Обработка ошибок при отправке данных
			});
	});

	// ALL click search
	var searchButton = document.querySelector(".download-search__search-button");

	searchButton?.addEventListener("click", function () {
		var searchInput = document.querySelector("#downloadSearch");

		if (document.querySelector(".popular-queries")) {
			document.querySelector(".popular-queries").style.display = "none";
		}

		if (document.querySelector(".sidebar-right__manual.manual")) {
			document.querySelector(".sidebar-right__manual.manual").style.display =
				"block";
		}

		if (document.querySelector(".sidebar-right__oc")) {
			document.querySelector(".sidebar-right__oc").style.display = "block";
		}

		var searchTerm = searchInput?.value;

		if (window.location.href.includes("faq")) {
			FaqSearch(searchTerm);
		} else if (window.location.href.includes("manual")) {
			ManualSearch(searchTerm);
		} else if (window.location.href.includes("update")) {
			UpdateSearch(searchTerm);
		}

		if (
			window.location.href.includes("faq") ||
			window.location.href.includes("manual") ||
			window.location.href.includes("update") ||
			window.location.href.includes("driver")
		) {
			resetSelectValue();
		}
	});

	// FAQ search
	function FaqSearch(searchValue) {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		fetch(ajaxurl, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "action=search_faq&searchTerm=" + searchValue,
		})
			.then(function (response) {
				return response.text();
			})
			.then(function (data) {
				document.querySelector(".faq-container").innerHTML = data;
				openFaqPopup();
			})
			.catch(function (error) {
				console.error("Error:", error);
			});
	}

	// Manual search
	function ManualSearch(searchValue) {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let container = document.querySelector(".sidebar-right__manual.manual");

		fetch(ajaxurl, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "action=search_manual&searchTerm=" + searchValue,
		})
			.then(function (response) {
				return response.text();
			})
			.then(function (data) {
				container.innerHTML = data;
			})
			.catch(function (error) {
				console.error("Error:", error);
			});
	}

	// Update search
	function UpdateSearch(searchValue) {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let container = document.querySelector(".sidebar-right__manual.firmware");

		fetch(ajaxurl, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "action=search_update&searchTerm=" + searchValue,
		})
			.then(function (response) {
				return response.text();
			})
			.then(function (data) {
				container.innerHTML = data;
			})
			.catch(function (error) {
				console.error("Error:", error);
			});
	}

	// Opcii search
	function OpciiSearch(searchValue) {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let container = document.querySelector(".consumables__list.opcii");

		fetch(ajaxurl, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "action=search_opcii&searchTerm=" + searchValue,
		})
			.then(function (response) {
				return response.text();
			})
			.then(function (data) {
				container.innerHTML = data;
			})
			.catch(function (error) {
				console.error("Error:", error);
			});
	}

	// Product search
	function ProductSearch(searchValue) {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
		let container = document.querySelector(".consumables__list.laser");

		fetch(ajaxurl, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "action=search_product&searchTerm=" + searchValue,
		})
			.then(function (response) {
				return response.text();
			})
			.then(function (data) {
				container.innerHTML = data;
			})
			.catch(function (error) {
				console.error("Error:", error);
			});
	}

	// search page product

	const searchValueProduct = async (searchValue) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_product");
		data.append("searchValue", searchValue);

		const containerProduct = document?.querySelector(
			".consumables__list.laser"
		);
		const containerPagination = document?.querySelector(
			".product .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();

				if (dataFetch.success) {
					if (containerProduct) {
						containerProduct.innerHTML = dataFetch.data.outputProducts;
						document.querySelector(".items-counter.product").textContent =
							"(" + dataFetch.data.total_posts_products + ")";

						if (dataFetch.data.total_posts_products === 0) {
							containerPagination.style.display = "none";
						} else {
							containerPagination.style.display = "flex";
						}
					}

					if (containerPagination) {
						containerPagination.innerHTML =
							dataFetch.data.outputPaginationProdD;
					}

					setMaskHeight();
					paginationReset();

					if (
						dataFetch.data.has_more_posts_product &&
						containerProduct?.children.length >= 6
					) {
						document.querySelector(".news-pagination.product").style.display =
							"flex";
						updatePaginationCounterVideo(
							1,
							dataFetch.data.total_pages_products
						);
						globalPagesNumber = dataFetch.data.total_pages_products;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// search page opcii

	const searchValueOpcii = async (searchValue) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_opcii");
		data.append("searchValue", searchValue);

		const containerProduct = document?.querySelector(
			".consumables__list.opcii"
		);
		const containerPagination = document?.querySelector(
			".opcii .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					if (containerProduct) {
						containerProduct.innerHTML = dataFetch.data.outputOpcii;
						document.querySelector(".items-counter.opcii").textContent =
							"(" + dataFetch.data.total_posts_opcii + ")";
					}

					if (containerPagination) {
						containerPagination.innerHTML = dataFetch.data.htmlPaginationOpciiS;
					}

					setMaskHeight();
					paginationReset();

					if (
						dataFetch.data.has_more_posts_opcii &&
						containerProduct?.children.length >= 1
					) {
						document.querySelector(".news-pagination.opcii").style.display =
							"flex";
						updatePaginationCounterVideo(1, dataFetch.data.total_pages_opcii);
						globalPagesNumber = dataFetch.data.total_pages_opcii;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// search page news

	const searchValueNews = async (searchValue) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_news");
		data.append("searchValue", searchValue);

		const containerProduct = document?.querySelector(
			".search-page__news-list.news"
		);
		const containerPagination = document?.querySelector(
			".news .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					if (containerProduct) {
						containerProduct.innerHTML = dataFetch.data.outputNews;
						document.querySelector(".items-counter.news").textContent =
							"(" + dataFetch.data.total_posts_news + ")";
					}

					if (containerPagination) {
						containerPagination.innerHTML = dataFetch.data.htmlPaginationNews;
					}

					paginationReset();
					if (
						dataFetch.data.has_more_posts_news &&
						containerProduct?.children.length >= 6
					) {
						document.querySelector(".news-pagination.news").style.display =
							"flex";
						updatePaginationCounterVideo(1, dataFetch.data.total_pages_news);
						globalPagesNumber = dataFetch.data.total_pages_news;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// // search page driver

	const searchValueDrive = async (searchValue) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_drive");
		data.append("searchValue", searchValue);

		const containerProduct = document?.querySelector(".driver-container");
		const containerPagination = document?.querySelector(
			".drive .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					if (containerProduct) {
						containerProduct.innerHTML = dataFetch.data.outputDrive;
						document.querySelector(
							".items-counter.driver-and-manual"
						).textContent = "(" + dataFetch.data.total_posts_drive + ")";
					}

					if (containerPagination) {
						containerPagination.innerHTML = dataFetch.data.htmlPaginationDrive;
					}

					paginationReset();
					if (
						dataFetch.data.has_more_posts_drive &&
						containerProduct?.children.length >= 6
					) {
						document.querySelector(".news-pagination.news").style.display =
							"flex";
						updatePaginationCounterVideo(1, dataFetch.data.total_pages_drive);
						globalPagesNumber = dataFetch.data.total_pages_drive;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// // search page manual

	const searchValueManual = async (searchValue) => {
		const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

		data.append("action", "load_sidebar_manualS");
		data.append("searchValue", searchValue);

		const containerProduct = document?.querySelector(
			".search-page__news-list.manual"
		);
		const containerPagination = document?.querySelector(
			".manual .news-pagination__list"
		);

		try {
			const response = await fetch(ajaxurl, {
				method: "POST",
				body: data,
			});

			if (response.ok) {
				const dataFetch = await response.json();
				if (dataFetch.success) {
					if (containerProduct) {
						containerProduct.innerHTML = dataFetch.data.outputManual;
					}

					if (containerPagination) {
						containerPagination.innerHTML = dataFetch.data.htmlPaginationManual;
					}

					paginationReset();
					if (
						dataFetch.data.has_more_posts_manual &&
						containerProduct?.children.length >= 6
					) {
						document.querySelector(".news-pagination.manual").style.display =
							"flex";
						updatePaginationCounterVideo(1, dataFetch.data.total_pages_manual);
						globalPagesNumber = dataFetch.data.total_pages_manual;
					}
				}
			} else {
				console.error("Network response was not ok");
			}
		} catch (error) {
			console.error("Fetch error:", error);
		}
	};

	// search page click

	var searchInput = document?.getElementById("searchVal");
	var searchButton = document?.querySelector(".site-search__button");

	searchButton?.addEventListener("click", function () {
		var searchTerm = searchInput.value;
		createLoader();
		searchValueProduct(searchTerm);
		searchValueOpcii(searchTerm);
		searchValueNews(searchTerm);
		searchValueDrive(searchTerm);
		searchValueManual(searchTerm);
		setTimeout(() => {
			removeLoader();
		}, 300);
	});

	// Тут начинается код страницы поиска
	if (
		document.querySelector(".news-pagination__counter") &&
		window.location.href.includes("rezultaty-poiska")
	) {
		document.querySelector(".news-pagination__counter").style.display = "none";
		let parent = document.querySelector(".news-pagination__counter").closest('.news-pagination.product');

		createLoader();
		setTimeout(() => {
			document.querySelector(".news-pagination.product").style.display = "flex";
			if (parent.style.display !== "none") {
				setTimeout(() => {
					document.querySelector(".news-pagination__counter").style.display =
						"block";
				}, 1000);
			}
			removeLoader();
		}, 1000);
	}

	const mainField = document.querySelector("#topSearchVal");

	mainField.addEventListener("change", () => {
		let value = mainField.value;
		localStorage.setItem("inputValue", value);
	});

	if (
		window.location.href.includes("rezultaty-poiska") &&
		localStorage.getItem("inputValue")
	) {
		const searchField = document.querySelector(".site-search__field");
		const searchButton = document.querySelector(".site-search__button");
		let value = localStorage.getItem("inputValue");

		mainField.value = "";
		searchField.value = value;
		searchField.textContent = value;

		setTimeout(() => {
			searchButton.click();
		}, 300);
	}

	if (window.location.href.includes("rezultaty-poiska")) {
		const titles = document?.querySelectorAll(".sidebar-left__title");

		titles.forEach((title) => {
			titles[0].classList.add("active");
			title.addEventListener("click", (evt) => {
				const currentElement = document?.querySelector(
					".sidebar-left__title.active"
				);

				if (currentElement !== evt.currentTarget) {
					currentElement?.classList.remove("active");

					if (currentElement.closest(".search-drivers")) {
						currentElement.closest(".search-drivers").classList.remove("open");
						currentElement
							.closest(".search-drivers")
							.querySelector(".accordion__content").style.height = 0 + "px";
					}
				}

				title.classList.add("active");
			});
		});

		const categoryList = document.querySelector(".sidebar-left__list");
		const productsCategory = document.querySelector(".search-page__products");
		const consumablesCategory = document.querySelector(
			".search-page__consumables"
		);
		const newsCategory = document.querySelector(".search-page__news");
		const driversCategory = document.querySelector(".search-page__drivers");
		const manualCategory = document.querySelector(".search-page__manual");

		categoryList.addEventListener("click", (evt) => {
			let pagination = document.querySelectorAll(".news-pagination__item");

			if (evt.target.closest(".search-products")) {
				productsCategory.style.display = "block";
				productsCategory.querySelector(".news-pagination").style.display =
					"block";
				pagination.forEach((item) => {
					item.remove();
				});
				var searchTerm = searchInput.value;

				searchValueProduct(searchTerm);
				createLoader();
				setTimeout(() => {
					removeLoader();
					paginationReset();
				}, 1000);
			} else {
				productsCategory.style.display = "none";
				productsCategory.querySelector(".news-pagination").style.display =
					"none";
			}

			if (evt.target.closest(".search-consumables")) {
				consumablesCategory.style.display = "block";
				consumablesCategory.querySelector(".news-pagination").style.display =
					"block";
				pagination.forEach((item) => {
					item.remove();
				});
				var searchTerm = searchInput.value;
				searchValueOpcii(searchTerm);
				createLoader();
				setTimeout(() => {
					removeLoader();
					paginationReset();
				}, 1000);
			} else {
				consumablesCategory.style.display = "none";
				consumablesCategory.querySelector(".news-pagination").style.display =
					"none";
			}

			if (evt.target.closest(".search-news")) {
				newsCategory.style.display = "block";
				newsCategory.querySelector(".news-pagination").style.display = "block";
				pagination.forEach((item) => {
					item.remove();
				});
				var searchTerm = searchInput.value;
				searchValueNews(searchTerm);
				createLoader();
				setTimeout(() => {
					removeLoader();
					paginationReset();
				}, 1000);
			} else {
				newsCategory.style.display = "none";
				newsCategory.querySelector(".news-pagination").style.display = "none";
			}

			if (evt.target.closest(".search-drivers")) {
				driversCategory.style.display = "block";
				driversCategory.querySelector(".news-pagination").style.display =
					"block";

				const manualButton = document.querySelector(".search-manual-button");
				const driverButton = document.querySelector(".search-driver-button");
				pagination.forEach((item) => {
					item.remove();
				});
				var searchTerm = searchInput.value;
				searchValueDrive(searchTerm);
				createLoader();
				setTimeout(() => {
					removeLoader();
					paginationReset();
				}, 1000);
				manualButton.addEventListener("click", () => {
					manualCategory.style.display = "block";
					manualCategory.querySelector(".manual-search").style.display =
						"block";
					manualCategory.querySelector(".news-pagination").style.display =
						"block";

					driversCategory.querySelector(".driver-container").style.display =
						"none";
					driversCategory.querySelector(".news-pagination").style.display =
						"none";

					var searchTerm = searchInput.value;
					searchValueDrive(searchTerm);
					createLoader();
					setTimeout(() => {
						removeLoader();
						paginationReset();
					}, 1000);
				});

				driverButton.addEventListener("click", () => {
					driversCategory.querySelector(".driver-container").style.display =
						"block";
					driversCategory.querySelector(".news-pagination").style.display =
						"block";

					manualCategory.style.display = "none";

					// driversCategory.style.display = "block";

					manualCategory.querySelector(".manual-search").style.display = "none";

					var searchTerm = searchInput.value;
					searchValueManual(searchTerm);
					createLoader();
					setTimeout(() => {
						removeLoader();
						paginationReset();
					}, 1000);
				});
			} else {
				driversCategory.style.display = "none";
				driversCategory.querySelector(".news-pagination").style.display =
					"none";

				manualCategory.style.display = "none";
				manualCategory.querySelector(".manual__list").style.display = "none";
				manualCategory.querySelector(".manual").style.display = "none";
			}
		});
	}
}

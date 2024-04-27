import { createLoader, removeLoader } from "./openSelect";
export default function findDriver() {
	// Функция для определения операционной системы пользователя
	function detectOS() {
		var userAgent = window.navigator.userAgent,
			platform = window.navigator.platform,
			macosPlatforms = ["Macintosh", "MacIntel", "MacPPC", "Mac68K"],
			windowsPlatforms = ["Win32", "Win64", "Windows", "WinCE"],
			iosPlatforms = ["iPhone", "iPad", "iPod"],
			os = null;

		if (macosPlatforms.indexOf(platform) !== -1) {
			os = "Mac OS";
		} else if (iosPlatforms.indexOf(platform) !== -1) {
			os = "iOS";
		} else if (windowsPlatforms.indexOf(platform) !== -1) {
			os = "Windows";
		} else if (/Android/.test(userAgent)) {
			os = "Android";
		} else if (/Linux/.test(platform)) {
			os = "Linux";
		}

		return os;
	}

	var userOS = detectOS();

	const data = new FormData();
	const oc = document?.querySelector(".oc");
	const systemPrimary = oc?.querySelector(".oc__system");
	const changeSystemButton = oc?.querySelector(".oc__change-button");
	const changeSystemField = oc?.querySelector(".oc__change-system");
	const acceptSystemButton = oc?.querySelector(".oc__change-oc-button");
	const systemName = oc?.querySelector(".oc__name");
	if (oc) {
		systemName.textContent = userOS;

		switch (systemName.textContent) {
			case "Windows":
				systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/win.png")`;
				break;
			case "Mac OS":
				systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/mac.svg")`;
				break;
			case "Linux":
				systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/linux.png")`;
				systemName.style.backgroundSize = " 24px";
				break;
		}

		const systemNameValue = oc?.querySelector(".video-search__select-title ");
		let oper = document.querySelector(".video-search__select-title.op");
		oper.textContent = userOS;
		let version = document.querySelector(
			".video-search__select-title.version-select"
		);
		let seriesParent = document.querySelector(".series-parent");
		let modelParent = document.querySelector(".model-parent");
		let isActiveChild = false;
		let isCheckedOper = true;

		const resultList = document?.querySelector(".manual__list");

		changeSystemButton?.addEventListener("click", (evt) => {
			evt.preventDefault();

			isCheckedOper = false;
			systemPrimary.style.display = "none";
			changeSystemField.style.display = "block";
		});

		if (resultList?.children.length >= 1) {
			if (oc) {
				oc.style.borderBottom = "none";
			}
		}

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

		const previousDriveParentProxy = new Proxy(
			{
				value: document.querySelector(".video-search__select-title.op")
					?.textContent,
			},
			{
				set: function (target, key, value) {
					if (target.value !== value) {
						document.querySelector(
							".video-search__select-title.version-select"
						).textContent = "Версия";
						isActiveChild = true;
						target.value = value;
					}
					return true;
				},
			}
		);

		const previousDriveModelProxy = new Proxy(
			{ value: document.querySelector(".model-parent")?.textContent },
			{
				set: function (target, key, value) {
					if (target.value !== value) {
						document.querySelector(
							".video-search__select-title.version-select"
						).textContent = "Версия";
						document.querySelector(
							".video-search__select-title.op"
						).textContent = "Операционная система";
						target.value = value;
					}
					return true;
				},
			}
		);

		acceptSystemButton?.addEventListener("click", (evt) => {
			evt.preventDefault();
			systemPrimary.style.display = "block";
			changeSystemField.style.display = "none";
			isCheckedOper = true;

			sendSelectedDriver(
				seriesParent.textContent,
				modelParent.textContent,
				oper.textContent,
				version.textContent
			);

			if (systemNameValue.textContent !== "Операционная система") {
				let systemNowValue = systemNameValue.textContent;

				if (systemNowValue !== "") {
					systemName.textContent = systemNowValue;
				} else {
					systemName.textContent = oc__name;
				}
			}

			switch (systemName.textContent) {
				case "Windows":
					systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/win.png")`;
					break;
				case "Mac OS":
					systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/mac.svg")`;
					break;
				case "Linux":
					systemName.style.backgroundImage = `url("/wp-content/themes/pantum/assets/img/linux.png")`;
					systemName.style.backgroundSize = " 24px";
					break;
			}
		});

		// ALL click search
		var searchInput = document?.getElementById("downloadSearch");
		var searchButton = document?.querySelector(
			".download-search__search-button"
		);
		searchButton?.addEventListener("click", function () {
			var searchTerm = searchInput.value;

			if (window.location.href.includes("driver")) {
				DriverSearch(searchTerm, oper.textContent);
			}
		});

		// Driver search
		function DriverSearch(searchValue, operations) {
			const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
			let container = document.querySelector(".manual__list.driver");
			data.append("action", "search_driver");
			data.append("searchTerm", searchValue);
			data.append("operations", operations);

			fetch(ajaxurl, {
				method: "POST",
				body: data,
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

		// Добавил код с опен селекта на кликабельность самих селектов

		const selectButtons = document?.querySelectorAll(
			".video-search__select-title"
		);

		if (selectButtons) {
			selectButtons.forEach((button, index) => {
				button.addEventListener("click", async () => {
					if (!isActiveChild) {
						version?.classList.remove("active");
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

					const items = itemsList.querySelectorAll(
						".video-search__select-item"
					);

					items.forEach((item) => {
						if (item) {
							item.addEventListener("click", async () => {
								previousDriveParentProxy.value = oper?.textContent;
								previousDriveModelProxy.value = modelParent?.textContent;

								sendSelectedOpSys(oper.textContent);

								if (window.location.href.includes("driver")) {
									if (isCheckedOper) {
										sendSelectedDriver(
											seriesParent.textContent,
											modelParent.textContent,
											"",
											""
										);
									}
								}
							});
						}
					});
				});
			});
		}

		// function for filter
		const sendSelectedOpSys = async (selectedParent) => {
			const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";
			data.append("action", "load_selected_op");
			data.append("selectedParentDrive", selectedParent);

			try {
				const response = await fetch(ajaxurl, {
					method: "POST",
					body: data,
				});

				if (response.ok) {
					const dataFetch = await response.json();
					if (dataFetch.success) {
						const html = dataFetch.data.outputFilterDrive;
						const tempDiv = document.createElement("div");
						tempDiv.innerHTML = html.trim();
						const childListContainer = document.querySelector(
							".video-search__select-list.version"
						);

						childListContainer.innerHTML = "";
						const childElements = tempDiv.querySelectorAll(
							".video-search__select-item.version"
						);

						childElements.forEach((childElement) =>
							childListContainer.appendChild(childElement)
						);
					}
				} else {
					console.error("Network response was not ok");
				}
			} catch (error) {
				console.error("Fetch error:", error);
			}
		};

		// Отрисовка контента

		// Код для драйверов
		const sendSelectedDriver = async (
			changeSeries,
			changeModel,
			oper,
			versionChange
		) => {
			const ajaxurl = window.location.origin + "/wp-admin/admin-ajax.php";

			data.append("action", "load_sidebar_driver");
			if (changeModel && changeModel !== "Модель") {
				data.append("changeModel", changeModel);
			}
			if (oper !== "Операционная система") {
				data.append("oper", oper);
			} else {
				oper = "";
				data.append("oper", oper);
			}
			if (versionChange !== "Версия") {
				data.append("versionChange", versionChange);
			} else {
				versionChange = "";
				data.append("versionChange", versionChange);
			}
			if (changeSeries && changeSeries !== "Серия") {
				data.append("changeSeries", changeSeries);
			}

			const container = document.querySelector(".manual__list.driver");

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
							container.innerHTML = dataFetch.data.outputDriver;
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
	}
}

import openSelect from "./openSelect";

export default function showDownloadFiles() {
	const filesInput = document.querySelector(".suggest-form__load");
	const preloadList = document.querySelector(".suggest-form__preload-list");
	filesInput?.addEventListener("change", (evt) => {
		let files = evt.target.files;
		let fileListArray = Array.from(files);

		for (let i = 0; i < files.length; i++) {
			let fileName = files[i].name;

			let fileElement = document.createElement("li");
			fileElement.classList.add("suggest-form__preload-item");
			fileElement.textContent = fileName;

			let removeButton = document.createElement("button");
			removeButton.classList.add("remove-button");
			removeButton.setAttribute("type", "button");
			fileElement.append(removeButton);

			if (files) {
				preloadList.style.display = "flex";
				preloadList.append(fileElement);
			} else {
				preloadList.style.display = "none";
			}

			removeButton.addEventListener("click", () => {
				fileElement.remove();
				fileListArray.splice(files[i], 1);
				return fileListArray;
			});
		}
	});
}

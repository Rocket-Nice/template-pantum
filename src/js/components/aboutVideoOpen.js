const openButton = document.querySelector(".about__video-button");
const videoMask = document.querySelector(".about__video-mask");
const aboutVideo = document.querySelector(".about__video");

export default function playVideo() {
	openButton?.addEventListener("click", () => {
		videoMask.style.display = "none";
		openButton.style.display = " none;";
		aboutVideo.play();
	});
}

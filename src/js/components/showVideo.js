export default function showVideo() {
  const videosList = document?.querySelectorAll(".video-item");
  const videoModal = document?.querySelector(".modal-show-video");
  const videoModalWrap = videoModal?.querySelector(".modal-show-video__wrapper");
  const video = videoModal?.querySelector(".modal-show-video__video");
  const closeVideo = document?.querySelector(".modal-show-video__close");

  videosList.forEach((el) => {
    el.addEventListener("click", (evt) => {
      evt.preventDefault;
      videoModal.classList.add("show");
      videoModalWrap.classList.add("scale");
      video.src = el.dataset.video;
    });

    closeVideo.addEventListener("click", () => {
      videoModal.classList.remove("show");
      videoModalWrap.classList.remove("scale");
      video.pause();
    });

    videoModal.addEventListener("click", (evt) => {
      if (!evt.target.closest(".modal-show-video__wrapper")) {
        videoModal.classList.remove("show");
        videoModalWrap.classList.remove("scale");
        video.pause();
      }
    });
  });
}

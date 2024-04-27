const dots = document?.querySelectorAll(".map-dot");

export default function setMapPosition() {
  dots?.forEach((dot) => {
    dot.style.top = dot.dataset.top + "px";
    dot.style.left = dot.dataset.left + "px";
  });
}

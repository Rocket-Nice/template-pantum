import { Fancybox } from "@fancyapps/ui";

export default function qualifyOpenImg() {
  Fancybox.bind("[data-fancybox='qualify-gallery']", {
    zoom: false,
    Carousel: {
      infinite: true,
      transition: "slide",
    },

    Images: {
      Panzoom: {
        zoom: false,
        zoomOpacity: true,
      },
    },
    Toolbar: {
      display: {
        right: ["close"],
      },
    },
    Thumbs: false,
  });
}

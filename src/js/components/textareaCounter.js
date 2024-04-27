const textField = document.querySelector(".suggest-form__message");
const counter = document.querySelector(".counter-text__current");

textField?.addEventListener("input", onInput);

export default function onInput(evt) {
  const length = evt.target.value.length;
  counter.innerText = length;
}

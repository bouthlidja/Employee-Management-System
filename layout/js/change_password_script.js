document.addEventListener("DOMContentLoaded", function () {
  const gridContainer = document.querySelector(".grid-container");

  // تحقق إذا كان العنصر "login" موجودًا
  if (document.getElementById("changePass")) {
    gridContainer.classList.add("boxEmail");
  }
});

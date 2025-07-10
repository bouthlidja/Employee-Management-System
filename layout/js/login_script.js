// console.log("login");

document.addEventListener("DOMContentLoaded", function () {
  const gridContainer = document.querySelector(".grid-container");

  // تحقق إذا كان العنصر "login" موجودًا
  if (document.getElementById("login")) {
    gridContainer.classList.add("login");
  }
});

let btnShowPassword = document.getElementById("showPassword");
let inputPassword = document.querySelector(".psswrd");

if (btnShowPassword && inputPassword) {
  btnShowPassword.onclick = () => {
    if (btnShowPassword.checked === true) {
      inputPassword.type = "text";
    } else {
      inputPassword.type = "password";
    }
  };
}

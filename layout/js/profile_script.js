document.querySelector("#profile .alert .close").onclick = () => {
  document.querySelector("#profile .alert").style.display = "none";
};

document.getElementById("showPassword").addEventListener("change", function () {
  let passInput = document.querySelector(".pass");
  passInput.type = this.checked ? "text" : "password";
});

function update() {
  let id = document.querySelector(".id").value;
  let usrNm = document.querySelector(".usrNm").value;
  let pass = document.querySelector(".pass").value;
  let eml = document.querySelector(".eml").value;
  let phn = document.querySelector(".phn").value;

  const xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status == 200) {
      const response = JSON.parse(xhr.response);
      let alertBox = document.querySelector("#profile .alert");
      let alertText = document.querySelector("#profile .alert .text");

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
      } else if (response.status === "error") {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
      }

      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_profile.php?action=update&id=${id}&usrNm=${usrNm}&pass=${pass}&eml=${eml}&phn=${phn}`,
    true
  );
  xhr.send();
}

document.querySelector(".btn-save").onclick = update;

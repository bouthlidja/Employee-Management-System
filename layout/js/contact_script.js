document.querySelector(".SendEmail .alert .close").onclick = () => {
  document.querySelector(".SendEmail .alert").style.display = "none";
};
document.getElementById("send").addEventListener("click", function (event) {
  // Prevent page reload
  event.preventDefault();
  let formData = new FormData(document.querySelector(".formEmail"));
  let xhr = new XMLHttpRequest();

  xhr.open("POST", "http://localhost/app%20EMS/api/api_send_email.php", true);

  let progressBar = document.getElementById("progressBar");
  progressBar.style.display = "block"; // Show progress bar
  progressBar.value = 2; //Start the counter from 2

  let isProcessing = true;

  // Update progress while uploading data to the server
  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      let percentComplete = (event.loaded / event.total) * 100;
      progressBar.value = percentComplete;
    }
  };

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      let alertBox = document.querySelector(".SendEmail .alert");
      let alertText = document.querySelector(".SendEmail .alert .text");

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertText.textContent = response.message;
      } else {
        alertBox.classList.add("alert-warning");
        alertText.textContent = response.message;
      }

      alertBox.style.display = "flex";

      // Gradually increase the progressBar value to 100% before hiding
      let interval = setInterval(() => {
        if (progressBar.value < 100) {
          // Gradual increase
          progressBar.value += 2;
        } else {
          clearInterval(interval);
          setTimeout(() => {
            alertBox.style.display = "none";
            alertBox.classList.remove("alert-success", "alert-warning");
            // Hide the progress bar after the message appears
            progressBar.style.display = "none";
          }, 2000);
        }
      }, 100);
      // Process completed
      isProcessing = false;
    }
  };
  xhr.send(formData);
});

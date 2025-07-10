let alertBox = document.querySelector("#folder .alert");
let alertText = document.querySelector("#folder .alert .text");

document.querySelector(".btn-add-folder").onclick = function () {
  document.querySelector("#model-add").style.display = "block";
};

document.querySelector(".closeAdd").onclick = function () {
  document.querySelector("#model-add").style.display = "none";
};

document.querySelector("#folder .alert .close").onclick = () => {
  document.querySelector("#folder .alert").style.display = "none";
};
function add() {
  let empID = document.querySelector("#model-add .empID").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      console.log(response);
      if (response.status === "success") {
        document.querySelector("#model-add").style.display = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        read();
      } else if (response.status === "error") {
        document.querySelector("#model-add").style.display = "none";
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
    `http://localhost/app%20EMS/api/api_folders.php?action=create&id=${empID}`,
    true
  );
  xhr.send();
}
document.querySelector(".btn-save-folder").onclick = add;
// display all folder in the table
let folderData = [];

function read() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_folders.php?action=select",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      folderData = response.folder;
      displayFolder(folderData);
    }
  };
  xhr.send();
}

function displayFolder(data) {
  const tableBody = document.querySelector(".table-folder tbody");
  tableBody.innerHTML = "";
  data.forEach((folder) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${folder.FLDRID}</td>
      <td>${folder.EMPID}</td>
      <td>${folder.FULL_NAME}</td>
      <td>
        <button class="btn btn-success "  onclick="openFolder('${folder.FLDRID}')"> 
          <i class="fa-solid fa-folder-open"></i>
        </button>
      </td>
      <td>
        <button class="btn btn-success "  onclick="upload('${folder.FLDRID}')"> 
          <i class="fa-solid fa-upload"></i>
        </button>
      </td>
      <td>
        <button class="btn btn-danger btn-show-delete" onclick="getIDFolders('${folder.FLDRID}')"> <i class="fa-solid fa-trash"></i></button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}
read();

// delete

let modelDelete = document.querySelector("#model-delete");
function closeMdlDlt() {
  document.getElementById("model-delete").style.display = "none";
}
window.closeMdlDlt = closeMdlDlt;
let folderID = null;
function getIDFolders(id) {
  folderID = id;
  document.querySelector(".model-delete .fldID").textContent = id;
  modelDelete.style.display = "block";
}
window.getIDFolders = getIDFolders;
const btnDlt = document.querySelector(".model-delete .btn-delete");
btnDlt.onclick = () => {
  console.log(btnDlt);
};
function deleteFolder() {
  const fldID = document.querySelector(".model-delete .fldID").textContent;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      console.log(response);
      if (response.status === "success") {
        modelDelete.style.display = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        read();
      } else {
        modelDelete.style.display = "none";
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
    `http://localhost/app%20EMS/api/api_folders.php?action=delete&id=${folderID}`,
    true
  );
  xhr.send();
}
btnDlt.onclick = deleteFolder;
let selectedFolderID = "";

function upload(folderID) {
  selectedFolderID = folderID;
  document.getElementById("model-upload").style.display = "block";
  console.log(selectedFolderID);
}
window.upload = upload;
function closeModal() {
  document.getElementById("model-upload").style.display = "none";
}
window.closeModal = closeModal;
function uploadFile() {
  let fileInput = document.getElementById("fileInput");
  let file = fileInput.files[0];

  let formData = new FormData();
  formData.append("folderID", selectedFolderID);
  formData.append("file", file);

  fetch("http://localhost/app%20EMS/api/api_folders.php?action=upload", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      alert(data.message);
      if (data.status === "success") {
        closeModal();
      }
    })
    .catch((error) => console.error("Error:", error));
}
window.uploadFile = uploadFile;

//--------------------------------
function openFolder(folderID) {
  fetch(
    `http://localhost/app%20EMS/api/api_folders.php?action=open&folderID=${folderID}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        window.open(data.path, "_blank"); // فتح المجلد في نافذة جديدة
      } else {
        alert(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}
window.openFolder = openFolder;

function searchFld() {
  let val = document.querySelector("#searchInput").value.trim(); // إزالة الفراغات الزائدة

  if (val === "") {
    read();
    return;
  }

  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_folders.php?action=searshFld&val=${encodeURIComponent(
      val
    )}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.folder) {
        displayFolder(response.folder);
      }
    }
  };

  xhr.send();
}

document.querySelector("#searchInput").addEventListener("input", searchFld);

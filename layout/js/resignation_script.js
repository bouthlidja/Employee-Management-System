let alertBox = document.querySelector("#resignations .alert");
let alertText = document.querySelector("#resignations .alert .text");

document.querySelector("#resignations .alert .close").onclick = () => {
  document.querySelector("#resignations .alert").style.display = "none";
};

function readResignations() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_resignation.php?action=select",
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-resignation tbody");
      tableBody.innerHTML = "";
      response.resignations.forEach((resignation) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${resignation.RESGID}</td>
            <td>${resignation.EMPID}</td>
            <td>${resignation.FULLNAME}</td>
            <td>${resignation.RESGRREAS}</td>
            
            <td>${resignation.EMPCONFDAT}</td>
            <td>${resignation.RESGEFFDAT}</td>
            <td>
                <button class="btn btn-success"  onclick="printDes('${resignation.RESGID}')" ><i class="fa-solid fa-print"></i></button>
            </td>
            <td>
                <button class="btn btn-success"  onclick="showUpdateForm('${resignation.RESGID}','${resignation.RESGRREAS}','${resignation.EMPCONFDAT}', '${resignation.RESGEFFDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
               <button class="btn btn-danger btn-delete-leave" onclick="getIDResignation('${resignation.RESGID}')"><i class="fa-solid fa-trash"></i></button>
            </td>
            `;
        tableBody.appendChild(row);
      });
    }
  };

  xhr.send();
}
readResignations();
const btnAdd = document.querySelector(".btn-add-resignation");
const modelAddResignation = document.querySelector("#model-add-resignation");
const btnClsResignation = document.querySelector(
  "#model-add-resignation #btn-close-model"
);
btnAdd.onclick = () => {
  modelAddResignation.style.display = "block";
};
btnClsResignation.onclick = () => {
  modelAddResignation.style.display = "none";
};

//searsh employee
function searchEmp() {
  let id = document.querySelector("#model-add-resignation .empID").value;
  const detileEmpID = document.querySelector(
    "#model-add-resignation .detileEmp .empID"
  );
  const fullNameTilte = document.querySelector(
    "#model-add-resignation .detileEmp .fullNameTilte"
  );
  const detileEmpFullNm = document.querySelector(
    "#model-add-resignation .detileEmp .fullName"
  );
  let formResignation = document.querySelector(
    "#model-add-resignation .form-resignation"
  );
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      console.log(response);

      if (response.status === "success" && response.employees.length > 0) {
        const employee = response.employees[0];

        detileEmpID.textContent = `ID: ${employee.EMPID}`;
        fullNameTilte.textContent = "Name: ";
        detileEmpFullNm.textContent = `${employee.FRSTNMEMP} ${employee.LSTNMEMP}`;
        formResignation.style.display = "block";
      } else {
        detileEmpFullNm.textContent = "No employee found or error occurred.";
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_resignation.php?action=searchEmp&id=${id}`,
    true
  );

  xhr.send();
}

let btnSearchEmp = document.querySelector(
  "#model-add-resignation #btnSearshEmp"
);

btnSearchEmp.onclick = searchEmp;
function addResingation() {
  let empID = document.querySelector("#model-add-resignation .empID").value;
  let reason = document.querySelector("#model-add-resignation .reason").value;
  let reqDat = document.querySelector("#model-add-resignation .reqDat").value;
  let resDat = document.querySelector("#model-add-resignation .resDat").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status == "success") {
        modelAddResignation.style.display = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readResignations();
      } else {
        modelAddResignation.style.display = "none";
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
    `http://localhost/app%20EMS/api/api_resignation.php?action=insert&empID=${empID}&reas=${reason}&reqDat=${reqDat}&resDat=${resDat}`,
    true
  );

  xhr.send();
  document.querySelector("#model-add-resignation .empID").value = "";
  document.querySelector("#model-add-resignation .reason").value = "";
  document.querySelector("#model-add-resignation .reqDat").value = "";
  document.querySelector("#model-add-resignation .resDat").value = "";
}

let btnAddResignation = document.querySelector(
  "#model-add-resignation .btn-add-resignation"
);
btnAddResignation.onclick = addResingation;
// function update Resignatuin
const modelUpdateResignation = document.querySelector(
  ".model-update-resignation"
);
function formatDate(date) {
  const dateParts = date.split("-");
  if (dateParts.length !== 3) {
    throw new Error("Invalid date format. Please use 'day-month-year' format.");
  }
  return `${dateParts[2]}-${dateParts[1].padStart(
    2,
    "0"
  )}-${dateParts[0].padStart(2, "0")}`;
}
function showUpdateForm(id, reanon, reqDat, resDat) {
  document.querySelector(".model-update-resignation .resigID").value = id;
  document.querySelector(".model-update-resignation .reason").value = reanon;
  const formatReqDat = formatDate(reqDat);
  document.querySelector(".model-update-resignation .reqDat").value =
    formatReqDat;
  const formatResDat = formatDate(resDat);
  document.querySelector(".model-update-resignation .resDat").value =
    formatResDat;
  modelUpdateResignation.style.display = "block";
}
window.showUpdateForm = showUpdateForm;
function updateResignation() {
  let resigID = document.querySelector(
    ".model-update-resignation .resigID"
  ).value;
  let reason = document.querySelector(
    ".model-update-resignation .reason"
  ).value;
  let reqDat = document.querySelector(
    ".model-update-resignation .reqDat"
  ).value;
  let resDat = document.querySelector(
    ".model-update-resignation .resDat"
  ).value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      if (response.status == "success") {
        modelUpdateResignation.style.display = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readResignations();
      } else {
        modelUpdateResignation.style.display = "none";
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
    `http://localhost/app%20EMS/api/api_resignation.php?action=update&rsgID=${resigID}&reas=${reason}&reqDat=${reqDat}&resDat=${resDat}`,
    true
  );

  xhr.send();
}
document.querySelector(
  ".model-update-resignation .btn-update-resignation"
).onclick = updateResignation;
const btnClsUpd = document.querySelector(
  ".model-update-resignation .btnClsUpd"
);
btnClsUpd.onclick = function () {
  modelUpdateResignation.style = "none";
};

// function delete Resignation
const modelDeleteResignation = document.querySelector(
  ".model-delete-resignation"
);

const btnClsDlt = document.querySelector(".model-delete-resignation .clsDlt");

btnClsDlt.onclick = function () {
  modelDeleteResignation.style.display = "none";
};

let resignationIdToDelete = null;
function getIDResignation(id) {
  resignationIdToDelete = id;
  document.querySelector(".model-delete-resignation .idTrns").textContent = id;
  modelDeleteResignation.style.display = "block";
}
window.getIDResignation = getIDResignation;
function deleteResignation() {
  let id = document.querySelector(
    ".model-delete-resignation .idTrns"
  ).textContent;

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      if (response.status == "success") {
        modelDeleteResignation.style.display = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readResignations();
      } else {
        modelDeleteResignation.style.display = "none";
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
    `http://localhost/app%20EMS/api/api_resignation.php?action=delete&id=${id}`,
    true
  );
  xhr.send();
}
document.querySelector(".model-delete-resignation .btn-delete-resig").onclick =
  deleteResignation;
function printDes(idResig) {
  let id = idResig;

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_print_resignation.php?action=print&id=${id}`,
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        // فتح ملف الـ PDF في نافذة جديدة
        var blob = new Blob([xhr.response], { type: "application/pdf" });
        var url = URL.createObjectURL(blob);
        window.open(url, "_blank");
      }
    }
  };

  // تعيين استجابة البيانات على شكل blob (ملف PDF)
  xhr.responseType = "blob";

  xhr.send();
}
window.printDes = printDes;

function searchResg() {
  let val = document.querySelector("#searchInput").value;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_resignation.php?action=search&val=${val}`,
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-resignation tbody");
      tableBody.innerHTML = "";
      response.resignations.forEach((resignation) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${resignation.RESGID}</td>
            <td>${resignation.EMPID}</td>
            <td>${resignation.FULLNAME}</td>
            <td>${resignation.RESGRREAS}</td>
            
            <td>${resignation.EMPCONFDAT}</td>
            <td>${resignation.RESGEFFDAT}</td>
            <td>
                <button class="btn btn-success"  onclick="printDes('${resignation.RESGID}')" ><i class="fa-solid fa-print"></i></button>
            </td>
            <td>
                <button class="btn btn-success"  onclick="showUpdateForm('${resignation.RESGID}','${resignation.RESGRREAS}','${resignation.EMPCONFDAT}', '${resignation.RESGEFFDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
               <button class="btn btn-danger btn-delete-leave" onclick="getIDResignation('${resignation.RESGID}')"><i class="fa-solid fa-trash"></i></button>
            </td>
            `;
        tableBody.appendChild(row);
      });
    }
  };
  xhr.send();
}

document.querySelector("#searchInput").addEventListener("input", searchResg);

let alertBox = document.querySelector("#retirement .alert");
let alertText = document.querySelector("#retirement .alert .text");

document.querySelector("#retirement .alert .close").onclick = () => {
  document.querySelector("#retirement .alert").style.display = "none";
};

function readRetirement() {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_retirement.php?action=select",
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-retirement tbody");
      tableBody.innerHTML = "";
      response.retirements.forEach((retirement) => {
        const row = document.createElement("tr");
        row.innerHTML = `
              <td>${retirement.RETID}</td>
              <td>${retirement.EMPID}</td>
              <td>${retirement.FULLNAME}</td>
              <td>${retirement.RETREAS}</td>
              <td>${retirement.REQDAT}</td>
              <td>${retirement.APPDAT}</td>
               <td>
                 <button class="btn btn-success btn-delete-leave" onclick="printDes('${retirement.RETID}')"><i class="fa-solid fa-print"></i></button>
              </td>
              <td>
                  <button class="btn btn-success"  onclick="showUpdateForm('${retirement.RETID}','${retirement.RETREAS}','${retirement.REQDAT}','${retirement.APPDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
              </td>
              <td>
                 <button class="btn btn-danger btn-delete-leave" onclick="getIDRetirement('${retirement.RETID}')"><i class="fa-solid fa-trash"></i></button>
              </td>
              `;
        tableBody.appendChild(row);
      });
    }
  };
  xhr.send();
}
readRetirement();
let btnAddRetirement = document.querySelector(".btn-add-retirement");
let modelAddRetirement = document.querySelector(".model-add-retirement");
btnAddRetirement.onclick = function () {
  modelAddRetirement.style.display = "block";
};
let btnclsAddRetirement = document.querySelector(
  ".model-add-retirement .clsAdd"
);
btnclsAddRetirement.onclick = () => {
  modelAddRetirement.style.display = "none";
};
function searchEmp() {
  let id = document.querySelector(".model-add-retirement .empID").value;
  const detileEmpID = document.querySelector(
    ".model-add-retirement .detileEmp .empID"
  );
  const fullNameTilte = document.querySelector(
    ".model-add-retirement .detileEmp .fullNameTilte"
  );
  const detileEmpFullNm = document.querySelector(
    ".model-add-retirement .detileEmp .fullName"
  );
  let formRetirement = document.querySelector(
    ".model-add-retirement .form-retirement"
  );
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      // عرض بيانات الموظف إذا كانت موجودة
      if (response.status === "success" && response.employees.length > 0) {
        const employee = response.employees[0];

        detileEmpID.textContent = `ID: ${employee.EMPID}`;
        fullNameTilte.textContent = "Name: ";
        detileEmpFullNm.textContent = `${employee.FRSTNMEMP} ${employee.LSTNMEMP}`;
        formRetirement.style.display = "block";
      } else {
        detileEmpFullNm.textContent = "No employee found or error occurred.";
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_retirement.php?action=searchEmp&id=${id}`,
    true
  );

  xhr.send();
}

let btnSearchEmp = document.querySelector(
  ".model-add-retirement #btnSearshEmp"
);

btnSearchEmp.onclick = searchEmp;
// add retiment
function addRetiment() {
  let empID = document.querySelector(".model-add-retirement .empID").value;
  let reason = document.querySelector(".model-add-retirement .reason").value;
  let reqDat = document.querySelector(".model-add-retirement .reqDat").value;
  let appDat = document.querySelector(".model-add-retirement .appDat").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        modelAddRetirement.style.display = "none";
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readRetirement();
      } else {
        modelAddRetirement.style.display = "none";
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
    `http://localhost/app%20EMS/api/api_retirement.php?action=insert&empID=${empID}&reas=${reason}&reqDat=${reqDat}&appDat=${appDat}`,
    true
  );
  xhr.send();
  console.log(empID);
  console.log(reason);
  console.log(reqDat);
  console.log(appDat);
}
document.querySelector(".model-add-retirement .btn-add-retirement").onclick =
  addRetiment;
// update retirement

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
function showUpdateForm(id, reason, reqDat, appDat) {
  document.querySelector(".model-update .idRet").value = id;
  document.querySelector(".model-update .reason").value = reason;
  let formatreqDat = formatDate(reqDat);
  document.querySelector(".model-update .reqDat").value = formatreqDat;
  let formatAppDat = formatDate(appDat);
  document.querySelector(".model-update .appDat").value = formatAppDat;
  document.querySelector(".model-update").style.display = "block";
}
window.showUpdateForm = showUpdateForm;
document.querySelector(".model-update .clsUpdate").onclick = function () {
  document.querySelector(".model-update").style.display = "none";
};
function update() {
  let id = document.querySelector(".model-update .idRet").value;
  let reason = document.querySelector(".model-update .reason").value;
  let reqDat = document.querySelector(".model-update .reqDat").value;
  let appDat = document.querySelector(".model-update .appDat").value;

  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status == "success") {
        alertBox.classList.add("alert-success");
        document.querySelector(".model-update").style.display = "none";
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readRetirement();
      } else {
        document.querySelector(".model-update").style.display = "none";
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
    `http://localhost/app%20EMS/api/api_retirement.php?action=update&id=${id}&reas=${reason}&reqDat=${reqDat}&appDat=${appDat}`
  );
  xhr.send();
}
document.querySelector(".model-update .btn-update").onclick = update;
// delete retirement
let retirementIdToDelete = null;
function getIDRetirement(id) {
  retirementIdToDelete = id;
  document.querySelector(".model-delete-retirement .idRet").textContent = id;
  document.querySelector(".model-delete-retirement").style.display = "block";
}
window.getIDRetirement = getIDRetirement;

document.querySelector(".model-delete-retirement .clsDlt").onclick =
  function () {
    document.querySelector(".model-delete-retirement").style.display = "none";
  };

function deleteRetirement() {
  let id = document.querySelector(
    ".model-delete-retirement .idRet"
  ).textContent;

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        document.querySelector(".model-delete-retirement").style.display =
          "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readRetirement();
      } else {
        document.querySelector(".model-delete-retirement").style.display =
          "none";
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
    `http://localhost/app%20EMS/api/api_retirement.php?action=delete&id=${id}`,
    true
  );
  xhr.send();
}

document.querySelector(".model-delete-retirement  .btn-delete-ret").onclick =
  deleteRetirement;
function printDes(idRet) {
  let id = idRet;

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    ` http://localhost/app%20EMS/api/api_print_retirement.php?action=print&id=${id}`,
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        // تم استلام الاستجابة بنجاح
        console.log("تم إرسال الطلب بنجاح.");

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

function searchRet() {
  let val = document.querySelector("#searchInput").value;
  console.log(val);
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_retirement.php?action=search&val=${val}`,
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-retirement tbody");
      tableBody.innerHTML = "";
      response.retirements.forEach((retirement) => {
        const row = document.createElement("tr");
        row.innerHTML = `
              <td>${retirement.RETID}</td>
              <td>${retirement.EMPID}</td>
              <td>${retirement.FULLNAME}</td>
              <td>${retirement.RETREAS}</td>
              <td>${retirement.REQDAT}</td>
              <td>${retirement.APPDAT}</td>
              <td>
                 <button class="btn btn-success btn-delete-leave" onclick="printDes('${retirement.RETID}')"><i class="fa-solid fa-print"></i></button>
              </td>
              <td>
                  <button class="btn btn-success"  onclick="showUpdateForm('${retirement.RETID}','${retirement.RETREAS}','${retirement.REQDAT}','${retirement.APPDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
              </td>
              <td>
                 <button class="btn btn-danger btn-delete-leave" onclick="getIDRetirement('${retirement.RETID}')"><i class="fa-solid fa-trash"></i></button>
              </td>
              `;
        tableBody.appendChild(row);
      });
    }
  };
  xhr.send();
}

document.querySelector("#searchInput").addEventListener("input", searchRet);

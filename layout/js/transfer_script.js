let alertBox = document.querySelector("#transfer .alert");
let alertText = document.querySelector("#transfer .alert .text");

document.querySelector("#transfer .alert .close").onclick = () => {
  document.querySelector("#transfer .alert").style.display = "none";
};

// read transfers function
function readTransfer() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_transfers.php?action=select",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-transfer tbody");
      tableBody.innerHTML = "";
      response.transfers.forEach((transfer) => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${transfer.TRAID}</td>
        <td>${transfer.EMPID}</td>
        <td>${transfer.FULLNAME}</td>
        <td>${transfer.CURRWRKP}</td>
        <td>${transfer.DATORGDEPT}</td>
        <td>${transfer.DEPNAME}</td>
        <td>${transfer.RECDEPAPPDAT}</td>
        <td>${transfer.NEWWRKSTRDAT}</td>
        <td>
            <button class="btn btn-success"  onclick="printDes('${transfer.TRAID}')" ><i class="fa-solid fa-print"></i></button>
        </td>
        <td>
            <button class="btn btn-success"  onclick="showUpdateForm('${transfer.TRAID}','${transfer.CURRWRKP}','${transfer.DATORGDEPT}','${transfer.DEPID}','${transfer.RECDEPAPPDAT}','${transfer.NEWWRKSTRDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
        </td>
        <td>
           <button class="btn btn-danger btn-delete-leave" onclick="getIDTransfer('${transfer.TRAID}')"><i class="fa-solid fa-trash"></i></button>
        </td>
        `;
        tableBody.appendChild(row);
      });
    }
  };

  xhr.send();
}
readTransfer();
let btnAdd = document.querySelector(".btn-add-transfer");
let modelAddTransfer = document.querySelector(".model-add-transfer");
let btnClsAddTransfer = document.querySelector(
  ".model-add-transfer #btn-close-model"
);

function searchTransfer() {
  let val = document.querySelector("#searchInput").value;
  console.log(val);
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_transfers.php?action=searchTransfer&val=${val}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-transfer tbody");
      tableBody.innerHTML = "";
      response.transfers.forEach((transfer) => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${transfer.TRAID}</td>
        <td>${transfer.EMPID}</td>
        <td>${transfer.FULLNAME}</td>
        <td>${transfer.CURRWRKP}</td>
        <td>${transfer.DATORGDEPT}</td>
        <td>${transfer.DEPNAME}</td>
        <td>${transfer.RECDEPAPPDAT}</td>
        <td>${transfer.NEWWRKSTRDAT}</td>
         <td>
            <button class="btn btn-success"  onclick="printDes('${transfer.TRAID}')" ><i class="fa-solid fa-print"></i></button>
        </td>
        <td>
            <button class="btn btn-success"  onclick="showUpdateForm('${transfer.TRAID}','${transfer.CURRWRKP}','${transfer.DATORGDEPT}','${transfer.DEPID}','${transfer.RECDEPAPPDAT}','${transfer.NEWWRKSTRDAT}')" ><i class="fa-solid fa-pen-to-square"></i></button>
        </td>
        <td>
           <button class="btn btn-danger btn-delete-leave" onclick="getIDTransfer('${transfer.TRAID}')"><i class="fa-solid fa-trash"></i></button>
        </td>
        `;
        tableBody.appendChild(row);
      });
    }
  };
  xhr.send();
}

document
  .querySelector("#searchInput")
  .addEventListener("input", searchTransfer);

btnAdd.onclick = function () {
  modelAddTransfer.style.display = "block";
};
btnClsAddTransfer.onclick = function () {
  modelAddTransfer.style.display = "none";
};

// search enployee
function searchEmp() {
  let id = document.querySelector(".model-add-transfer .empID").value;
  const detileEmpID = document.querySelector(
    ".model-add-transfer .detileEmp .empID"
  );
  const fullNameTilte = document.querySelector(
    ".model-add-transfer .detileEmp .fullNameTilte"
  );
  const detileEmpFullNm = document.querySelector(
    ".model-add-transfer .detileEmp .fullName"
  );
  const orgDept = document.querySelector(".model-add-transfer .orgDept");
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success" && response.employees.length > 0) {
        const employee = response.employees[0];

        detileEmpID.textContent = `ID: ${employee.EMPID}`;
        fullNameTilte.textContent = "Name: ";
        detileEmpFullNm.textContent = `${employee.FULLNAME}`;
        orgDept.value = `${employee.DEPNAME}`;
        document.querySelector(".form-Add-transfer").style.display = "block";
      } else {
        detileEmpFullNm.textContent = "No employee found or error occurred.";
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_transfers.php?action=searchEmp&id=${id}`,
    true
  );

  xhr.send();
}

let btnSearchTrensfer = document.querySelector(
  ".model-add-transfer #btnSearshEmp"
);

btnSearchTrensfer.onclick = searchEmp;

// function add trensfer

function addtransfer() {
  let empID = document.querySelector(".model-add-transfer .empID").value;
  let orgDept = document.querySelector(".model-add-transfer #orgDept").value;
  let datOrgApp = document.querySelector(
    ".model-add-transfer #datOrgApp"
  ).value;
  let newDept = document.querySelector(".model-add-transfer #newDept").value;
  let datNewApp = document.querySelector(
    ".model-add-transfer #datNewApp"
  ).value;
  let strtDat = document.querySelector(".model-add-transfer #strtDat").value;
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        modelAddTransfer.style.display = "none";

        document.querySelector(
          ".model-add-transfer .detileEmp .empID"
        ).textContent = "";
        document.querySelector(
          ".model-add-transfer .detileEmp .fullNameTilte"
        ).textContent = "";
        document.querySelector(
          ".model-add-transfer .detileEmp .fullName"
        ).textContent = "";

        document.querySelector(".form-Add-transfer").style.display = "none";
        readTransfer();
      } else {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        modelAddTransfer.style.display = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_transfers.php?action=insert&OrgDept=${orgDept}&DatOrgApp=${datOrgApp}&NewDept=${newDept}&DatNewApp=${datNewApp}&StrtDat=${strtDat}&EmpID=${empID}`,
    true
  );

  xhr.send();

  document.querySelector(".model-add-transfer .empID").value = "";
  document.querySelector(".model-add-transfer .detileEmp .empID").textContent =
    "";

  document.querySelector(
    ".model-add-transfer .detileEmp .fullNameTilte"
  ).textContent = "";
  document.querySelector(".form-Add-transfer").style.display = "none";
  document.querySelector(".model-add-transfer .fullName").textContent = "";
  document.querySelector(".model-add-transfer #orgDept").value = "";
  document.querySelector(".model-add-transfer #datOrgApp").value = "";
  document.querySelector(".model-add-transfer #newDept").value = "";
  document.querySelector(".model-add-transfer #datNewApp").value = "";
  document.querySelector(".model-add-transfer #strtDat").value = "";
}

document.querySelector(".model-add-transfer .btn-add-transfer").onclick =
  addtransfer;
// update

const modelUpdate = document.querySelector(".model-update-transfer");

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

function showUpdateForm(id, curDep, datOrgDpt, deptID, datNewDptApp, strDat) {
  modelUpdate.style.display = "block";
  document.querySelector(".model-update-transfer .trnID").value = id;
  document.querySelector(".model-update-transfer .orgDept").value = curDep;
  let formattedatOrgDpt = formatDate(datOrgDpt);
  document.querySelector(".model-update-transfer #datOrgApp").value =
    formattedatOrgDpt;
  document.querySelector(".model-update-transfer #newDept").value = deptID;

  let formatteDatNewDptApp = formatDate(datNewDptApp);
  document.querySelector(".model-update-transfer #datNewApp").value =
    formatteDatNewDptApp;
  let formattestrDat = formatDate(strDat);
  document.querySelector(".model-update-transfer #strtDat").value =
    formattestrDat;
}
window.showUpdateForm = showUpdateForm;
const modelUpdCls = document.querySelector(
  ".model-update-transfer #btn-close-model"
);
modelUpdCls.onclick = () => {
  modelUpdate.style.display = "none";
};

function updateTransfer() {
  let trnID = document.querySelector(".model-update-transfer .trnID").value;

  let datOrgApp = document.querySelector(
    ".model-update-transfer #datOrgApp"
  ).value;
  let newDept = document.querySelector(".model-update-transfer #newDept").value;
  let datNewApp = document.querySelector(
    ".model-update-transfer #datNewApp"
  ).value;
  let strtDat = document.querySelector(".model-update-transfer #strtDat").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      console.log(response);
      if (response.status == "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector(".model-update-transfer").style.desplay = "none";
        readTransfer();
      } else {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector(".model-update-transfer").style.desplay = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_transfers.php?action=update&id=${trnID}&DatOrgApp=${datOrgApp}&NewDept=${newDept}&RecDepAppDat=${datNewApp}&StrtDat=${strtDat}`,
    true
  );
  xhr.send();

  console.log(trnID);
  console.log(datOrgApp);
  console.log(newDept);
  console.log(datNewApp);
  console.log(strtDat);
}
document.querySelector(".model-update-transfer .btn-update-transfer").onclick =
  updateTransfer;
// delete
let modelDelete = document.querySelector(".delete-transfer-box");
let transferIdToDelete = null;
function getIDTransfer(id) {
  transferIdToDelete = id;
  document.querySelector(".delete-transfer-box .idTrns").textContent = id;
  modelDelete.style.display = "block";
}
window.getIDTransfer = getIDTransfer;
const btnCloseModelDelete = document.getElementById("btn-close-model-delete");
btnCloseModelDelete.onclick = () => {
  modelDelete.style.display = "none";
};
function deleteTrandsfer() {
  let id = document.querySelector(".delete-transfer-box .idTrns").textContent;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      if (response.status == "success") {
        modelDelete.style.desplay = "none";
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readTransfer();
      } else {
        modelDelete.style.desplay = "none";
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
    `http://localhost/app%20EMS/api/api_transfers.php?action=delete&id=${id}`,
    true
  );
  xhr.send();
}
window.deleteTrandsfer = deleteTrandsfer;
document.querySelector(".delete-transfer-box .btn-delete-trns").onclick =
  deleteTrandsfer;

function printDes(idTrns) {
  let id = idTrns;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_print_trensfers.php?action=print&id=${id}`,
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

function showTab(index) {
  const tabs = document.querySelectorAll(".tab");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab, i) => {
    tab.classList.toggle("active", i === index);
  });
  contents.forEach((content, i) => {
    content.classList.toggle("active", i === index);
  });
}
window.showTab = showTab;

//strat transfer report

function reportTransfer() {
  let strtDat = document.querySelector("#reportTransfer .strtDat").value;
  let endtDat = document.querySelector("#reportTransfer .endtDat").value;
  console.log(strtDat, endtDat);
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    let response = JSON.parse(xhr.responseText);
    if (xhr.status === 200) {
      if (response.status == "success") {
        const tableBody = document.querySelector(
          "#reportTransfer .table-transfer tbody"
        );
        tableBody.innerHTML = "";
        response.transfers.forEach((transfer) => {
          const row = document.createElement("tr");
          row.innerHTML = `
          <td>${transfer.TRAID}</td>
          <td>${transfer.EMPID}</td>
          <td>${transfer.FULL_NAME}</td>
          <td>${transfer.DEPNAME}</td>
          <td>${transfer.ORGDEPAPPDAT}</td>
            <td>${transfer.RECDEPAPPDAT}</td>
           <td>${transfer.NEWWRKSTRDAT}</td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_transfers.php?action=reportTransfer&strtDat=${strtDat}&endDat=${endtDat}`,
    true
  );
  xhr.send();
}

document
  .querySelector("#reportTransfer .btn-rprt-trn")
  .addEventListener("click", reportTransfer);

document
  .querySelector("#reportTransfer .btn-rprt-trn")
  .addEventListener("click", () => {
    document.querySelectorAll("#reportTransfer .from").forEach((ele) => {
      ele.textContent = document
        .querySelector("#reportTransfer .strtDat")
        .value.split("-")
        .reverse()
        .join("-");
    });
    document.querySelectorAll("#reportTransfer .to").forEach((ele) => {
      ele.textContent = document
        .querySelector("#reportTransfer .endtDat")
        .value.split("-")
        .reverse()
        .join("-");
    });
  });

//end transfer report

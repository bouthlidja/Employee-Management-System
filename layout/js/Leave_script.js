let alertBox = document.querySelector("#leaves .alert");
let alertText = document.querySelector("#leaves .alert .text");

document.querySelector("#leaves .alert .close").onclick = () => {
  document.querySelector("#leaves .alert").style.display = "none";
};

const modelAdd = document.getElementById("model-add");
const btnAddModel = document.getElementById("btn-add-model");
const btnCloseModel = document.getElementById("btn-close-model");

btnAddModel.addEventListener("click", function () {
  modelAdd.style.display = "block";
});
btnCloseModel.addEventListener("click", function () {
  modelAdd.style.display = "none";
});
const modelUpdate = document.getElementById("model-update");
const btnClsMdlUpd = document.querySelector(".btn-close-model-update");

btnClsMdlUpd.addEventListener("click", function () {
  modelUpdate.style.display = "none";
});
const modelDelete = document.getElementById("model-delete");
const btnClsMdlDlt = document.getElementById("btn-close-model-delete");

btnClsMdlDlt.addEventListener("click", function () {
  modelDelete.style.display = "none";
});

// read all leaves
// تخزين بيانات الإجازات
let leavesData = [];

function readLeaves() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_leaves.php?action=select",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      leavesData = response.leaves; // تخزين البيانات لاستخدامها في الفلترة
      displayLeaves(leavesData); // عرض البيانات في الجدول
    }
  };

  xhr.send();
}

function displayLeaves(data) {
  const tableBody = document.querySelector(".table-Leave tbody");
  tableBody.innerHTML = "";
  data.forEach((leave) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${leave.LVSID}</td>
      <td>${leave.FULL_NAME}</td>
      <td>${leave.LVSTYP}</td>
      <td>${leave.LVSREAS}</td>
      <td>${leave.LVSDUR}</td>
      <td>${leave.START_DATE}</td>
      <td>${leave.END_DATE}</td>
      <td>${leave.LVSSTTS}</td>
      <td>
        <button class="btn btn-success" onclick="printser('${leave.LVSID}')"><i class="fa-solid fa-print"></i></button>
      </td>
      <td>
        <button class="btn btn-success" onclick="showUpdateForm('${leave.LVSID}', '${leave.LVSTYP}', '${leave.LVSREAS}', ${leave.LVSDUR}, '${leave.START_DATE}')"><i class="fa-solid fa-pen-to-square"></i></button>
      </td>
      <td>
        <button class="btn btn-danger btn-delete-leave" onclick="getIDLeave('${leave.LVSID}')"><i class="fa-solid fa-trash"></i></button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}
readLeaves();

// filter leaves
function filterLeaves() {
  let type = document.getElementById("filterType").value;
  let reason = document.getElementById("filterRsn").value;
  let status = document.getElementById("filterStts").value;
  // let startDate = document.getElementById("filterStartDate").value;
  // let endDate = document.getElementById("filterEndDate").value;

  let filteredData = leavesData.filter((leave) => {
    return (
      (type === "" || leave.LVSTYP === type) &&
      (reason === "" || leave.LVSREAS === reason) &&
      (status === "" || leave.LVSSTTS === status) //&&
      // (startDate === "" || leave.START_DATE >= startDate) &&
      // (endDate === "" || leave.END_DATE <= endDate)
    );
  });

  displayLeaves(filteredData); // تحديث الجدول بعد الفلترة
}

document.getElementById("filterType").addEventListener("change", filterLeaves);
document.getElementById("filterRsn").addEventListener("change", filterLeaves);
document.getElementById("filterStts").addEventListener("change", filterLeaves);

const btnSearshEmp = document.querySelector("#model-add #btnSearshEmp");
btnSearshEmp.onclick = function () {
  const id = document.querySelector(".model-add-Leave .empIDValue").value;
  const detileEmpID = document.querySelector(
    ".model-add-Leave .detileEmp .empID"
  );
  const fullNameTilte = document.querySelector(
    ".model-add-Leave .detileEmp .fullNameTilte"
  );
  const detileEmpFullNm = document.querySelector(
    ".model-add-Leave .detileEmp .fullName"
  );

  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      console.log(response);

      // عرض بيانات الموظف إذا كانت موجودة
      if (response.status === "success" && response.employees.length > 0) {
        const employee = response.employees[0];

        detileEmpID.textContent = `ID: ${employee.EMPID}`;
        fullNameTilte.textContent = "Name: ";
        detileEmpFullNm.textContent = `${employee.FRSTNMEMP} ${employee.LSTNMEMP}`;
        document.querySelector(".form-Add-leave").style.display = "block";
      } else {
        detileEmpFullNm.textContent = "No employee found or error occurred.";
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_leaves.php?action=searchEmp&empID=${id}`,
    true
  );

  xhr.send();
};
//  Search for leaves
function searchLev() {
  let val = document.querySelector("#searchInput").value;
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-Leave tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.leaves.forEach((leave) => {
          const row = document.createElement("tr");
          row.innerHTML = `
          <td>${leave.LVSID}</td>
          <td>${leave.FULL_NAME}</td>
          <td>${leave.LVSTYP}</td>
          <td>${leave.LVSREAS}</td>
          <td>${leave.LVSDUR}</td>
          <td>${leave.LVSSTRTDAT}</td>
          <td>${leave.LVSENDDAT}</td>
          <td>${leave.LVSSTTS}</td>
           <td>
        <button class="btn btn-success" onclick="printser('${leave.LVSID}')"><i class="fa-solid fa-print"></i></button>
      </td>
      <td>
        <button class="btn btn-success" onclick="showUpdateForm('${leave.LVSID}', '${leave.LVSTYP}', '${leave.LVSREAS}', '${leave.START_DATE}')"><i class="fa-solid fa-pen-to-square"></i></button>
      </td>
      <td>
        <button class="btn btn-danger btn-delete-leave" onclick="getIDLeave('${leave.LVSID}')"><i class="fa-solid fa-trash"></i></button>
      </td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_leaves.php?action=searchLev&val=${val}`,
    true
  );
  xhr.send();
}
document.querySelector("#searchInput").addEventListener("input", searchLev);
//  add new leave

const msgSuccess = document.querySelector(".alert-success");
const msgSuccessText = document.querySelector(".alert-success .text");
const msgError = document.querySelector(".model-add-Leave .alert-warning");
const msgErrorText = document.querySelector(
  ".model-add-Leave .alert-warning .text"
);
const btnAddLeave = document.querySelector("#model-add .btn-add-leave");
btnAddLeave.onclick = function () {
  let empID = document.querySelector("#model-add .empIDValue").value;
  let typLvs = document.querySelector("#model-add .typLvs").value;
  let rsnLvs = document.querySelector("#model-add .rsnLvs").value;
  let durLvs = document.querySelector("#model-add .durLvs").value;
  let lvsStrtDat = document.querySelector("#model-add .lvsStrtDat").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    const response = JSON.parse(xhr.responseText);
    console.log(response);
    if (response.status == "success") {
      alertBox.classList.add("alert-success");
      alertBox.style.display = "flex";
      alertText.textContent = response.message;

      document.querySelector("#model-add .typLvs").value = "";
      document.querySelector("#model-add .rsnLvs").value = "";
      document.querySelector("#model-add .durLvs").value = "";
      document.querySelector("#model-add .lvsStrtDat").value = "";
      document.querySelector("#model-add .empIDValue").value = "";
      document.querySelector(".detileEmp .empID").textContent = "";
      document.querySelector(".form-Add-leave").style.display = "none";
      modelAdd.style.display = "none";
      readLeaves();
    }
    if (response.status == "error") {
      alertBox.classList.add("alert-warning");
      alertBox.style.display = "flex";
      alertText.textContent = response.message;
      modelAdd.style.display = "none";
    }
    setTimeout(() => {
      alertBox.style.display = "none";
      alertBox.classList.remove("alert-success", "alert-warning");
    }, 3000);
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_leaves.php?action=insert&empID=${empID}&lvstyp=${typLvs}&lvsReas=${rsnLvs}&lvsDur=${durLvs}&lvsStrtDat=${lvsStrtDat}`,
    true
  );
  xhr.send();
};

function showUpdateForm(id, typ, rsn, dur, strDat) {
  document.querySelector("#model-update .lvsID").value = id;

  document.querySelector("#model-update .typLvs").value = typ;
  document.querySelector("#model-update .rsnLvs").value = rsn;
  document.querySelector("#model-update .durLvs").value = dur;

  // تحويل التنسيق من "jj/mm/yyyy" إلى "yyyy-mm-dd"
  const dateParts = strDat.split("-");
  // تقسيم التاريخ إلى [اليوم, الشهر, السنة]
  const formattedDate = `${dateParts[2]}-${dateParts[1].padStart(
    2,
    "0"
  )}-${dateParts[0].padStart(2, "0")}`;

  // تعيين التاريخ المحوّل كقيمة للحقل
  document.querySelector("#model-update .lvsStrtDat").value = formattedDate;

  // عرض النموذج
  modelUpdate.style.display = "block";
}
window.showUpdateForm = showUpdateForm;

document.querySelector("#model-update .btn-update-leave").onclick = () => {
  let id = document.querySelector("#model-update .lvsID").value;
  let typ = document.querySelector("#model-update .typLvs").value;
  let rsn = document.querySelector("#model-update .rsnLvs").value;
  let dur = document.querySelector("#model-update .durLvs").value;
  let strDat = document.querySelector("#model-update .lvsStrtDat").value;

  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    const response = JSON.parse(xhr.responseText);

    if (response.status == "success") {
      alertBox.classList.add("alert-success");
      alertBox.style.display = "flex";
      alertText.textContent = response.message;
      modelUpdate.style.display = "none";

      readLeaves();
    }
    if (response.status == "error") {
      alertBox.classList.add("alert-warning");
      alertBox.style.display = "flex";
      alertText.textContent = response.message;
      modelUpdate.style.display = "none";
    }
    setTimeout(() => {
      alertBox.style.display = "none";
      alertBox.classList.remove("alert-success", "alert-warning");
    }, 3000);
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_leaves.php?action=update&lvsID=${id}&lvstyp=${typ}&lvsReas=${rsn}&lvsDur=${dur}&lvsStrtDat=${strDat}`,
    true
  );
  xhr.send();
};

// delete

let leaveIdToDelete = null;
function getIDLeave(id) {
  leaveIdToDelete = id;
  document.querySelector(".delete-leaves-box .idLvs").textContent = id;
  modelDelete.style.display = "block";
}
window.getIDLeave = getIDLeave;
const btnDltLvs = document.querySelector(".delete-leaves-box .btn-delete-lvs");
// console.log(btnDltLvs);
function deleteleaves() {
  const idLvs = document.querySelector(".delete-leaves-box .idLvs").textContent;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        modelDelete.style.display = "none";
        readLeaves();
      } else {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        alertWarning.style.display = "flex";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_leaves.php?action=delete&id=${leaveIdToDelete}`,
    true
  );
  xhr.send();
}
btnDltLvs.onclick = deleteleaves;

function printser(idlvs) {
  let id = idlvs;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_print_leave.php?action=print&id=${id}`,
    true
  );

  xhr.onload = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        let blob = new Blob([xhr.response], { type: "application/pdf" });
        let url = URL.createObjectURL(blob);
        window.open(url, "_blank");
      }
    }
  };

  // تعيين استجابة البيانات على شكل blob (ملف PDF)
  xhr.responseType = "blob";

  xhr.send();
}
window.printser = printser;

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
// reportAnnual

function reportAnnual() {
  let year = document.querySelector(".box .searsh").value;
  console.log(year);
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      console.log(response);
      if (response.status == "error") {
        document.querySelector(".reportAnnLev .alert-warning").style.display =
          "flex";
        document.querySelector(
          ".reportAnnLev .alert-warning .text"
        ).textContent = response.message;
        console.log(response.message);
      } else {
        const tableBody1 = document.querySelector(".table-emp-Leave tbody");
        tableBody1.innerHTML = "";
        response.empLeave.forEach((emp) => {
          const row = document.createElement("tr");
          row.innerHTML = `
              <td>${emp.LVSID}</td>
              <td>${emp.EMPID}</td>
              <td>${emp.FULLNAME}</td>
              `;
          tableBody1.appendChild(row);
        });

        const tableBody2 = document.querySelector(".table-emp-no-Leave tbody");
        tableBody2.innerHTML = "";
        response.empNoLeave.forEach((empNo) => {
          const row = document.createElement("tr");
          row.innerHTML = `
              
              <td>${empNo.EMPID}</td>
              <td>${empNo.FULLNAME}</td>
              `;
          tableBody2.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_report.php?action=reportAnnual&year=${year}`,
    true
  );
  xhr.send();
}
console.log(document.querySelector(".box .btn-search"));
document
  .querySelector(".box .btn-search")
  .addEventListener("click", reportAnnual);
document.querySelector(".box .btn-search").addEventListener("click", () => {
  let year = document.querySelector(".box .searsh").value;
  document.querySelectorAll(".reportAnnLev .yyyy").forEach((ele) => {
    ele.textContent = year;
  });
});

document
  .querySelector(".reportAnnLev .inputSearsh")
  .addEventListener("input", () => {
    if (
      document.querySelector(".reportAnnLev .inputSearsh").value.trim() === ""
    ) {
      document.querySelector(".table-emp-Leave tbody").innerHTML = "";
      document.querySelector(".table-emp-no-Leave tbody").innerHTML = "";
      document.querySelectorAll(".reportAnnLev .yyyy").forEach((ele) => {
        ele.textContent = "";
      });
    }
  });

// Leave report by employee
function LeaveReportByEmployee() {
  let empID = document.querySelector(".box .empID").value;
  let startDate = document.querySelector(".box .startDate").value;
  let endDate = document.querySelector(".box .endDate").value;
  console.log(startDate);
  console.log(endDate);
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status == "success") {
        response.infoEmp.forEach((ele) => {
          document.querySelector(".LvRptEmp .infoEmp span").textContent =
            ele.EMPID;
          document.querySelector(".LvRptEmp .fullName span").textContent =
            ele.FULLNAME;
        });
        const tableBody = document.querySelector(
          ".LvRptEmp .table-info-Leave tbody"
        );
        tableBody.innerHTML = "";
        response.infoLvs.forEach((lvs) => {
          const row = document.createElement("tr");
          row.innerHTML = `
              <td>${lvs.LEAVE_TYPE}</td>
              <td>${lvs.TOTAL_LEAVES}</td>
              
              `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_report.php?action=LvRptEmp&empID=${empID}&startDate=${startDate}&endDate=${endDate}`,
    true
  );
  xhr.send();
}
document.querySelector(".box .btn-search-emp").onclick = LeaveReportByEmployee;

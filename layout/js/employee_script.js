// These variables are specific to the employees page.
const alertWarning = document.querySelector(".alert-warning");
const btnAlertWarning = document.querySelector(
  ".alert-warning .btn-close-alert"
);

const alertSuccess = document.querySelector(".alert-success");
const btnalertSuccess = document.querySelector(
  ".alert-success .btn-close-alert"
);

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
const btnAddEmp = document.querySelector(".btn-add-emp");
const modelAddEmployees = document.querySelector(".model-add-employees");
const modelEmpClose = document.querySelector(".model-add-employees .close");

// علامات التبويب
const modals = document.querySelectorAll(".modal");

//searsh
const btnSearshEmp = document.querySelector(".form-searsh .btn");
const inputSearch = document.querySelector(".form-searsh .searsh");

// const sectors = document.getElementById("sctr");
// const ranks = document.getElementById("rnk");

const btnUpdateEmployee = document.getElementById("updateEmployee");

const btnAddEmployee = document.getElementById("addEmployee");

const modelUpdateEmployees = document.querySelector(".model-update-employees");
const updateClose = document.querySelector(".model-update-employees .close");

const btnDeleteEmpClose = document.querySelector(".delete-Employee-box .close");

const boxDeleteEmp = document.querySelector(".delete-Employee-box");

const btnDeleteEmpSecondary = document.querySelector(
  ".delete-Employee-box .btn-secondary"
);

const empID = document.querySelector(".empID");
let empIdToDelete = null; // متغير عالمي لتخزين المعرف

const btnDeleteEmp = document.querySelector(
  ".delete-Employee-box .btn-delete-Emp"
);

if (alertWarning && btnAlertWarning) {
  btnAlertWarning.onclick = () => {
    alertWarning.style.display = "none";
  };
}

if (alertSuccess && btnalertSuccess) {
  btnalertSuccess.onclick = () => {
    alertSuccess.style.display = "none";
  };
}

if (btnAddEmp && modelAddEmployees && modelEmpClose) {
  btnAddEmp.onclick = () => {
    modelAddEmployees.style.display = "block";
  };
  modelEmpClose.onclick = () => {
    modelAddEmployees.style.display = "none";
  };
}

modals.forEach((modal) => {
  // اختيار التبويبات والمحتويات داخل الحاوية فقط
  const tabs = modal.querySelectorAll(".tab");
  const tabContents = modal.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // إزالة الفئة "active" من التبويبات والمحتويات في هذه الحاوية فقط
      tabs.forEach((t) => t.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      // إضافة الفئة "active" للتبويب والمحتوى المحدد
      tab.classList.add("active");
      modal
        .querySelector(`.tab-content.${tab.dataset.tab}`)
        .classList.add("active");
    });
  });
});
//  -----------------------------------------------------------------------
// function searsh

function searshEmployees() {
  const val = document.querySelector(".form-searsh .searsh").value;
  var xhr = new XMLHttpRequest();

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_employees.php?action=search&val=${val}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-employees tbody");

      if (tableBody) {
        tableBody.innerHTML = ""; // مسح محتوى الجدول قبل إضافة البيانات الجديدة
        response.employees.forEach((employee) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${employee.EMPID}</td>
            <td>${employee.FRSTNMEMP}</td>
            <td>${employee.LSTNMEMP}</td>
            <td>${employee.GNDEMP}</td>
            <td>${employee.EMPDATBRTH}</td>
            <td>${employee.MUNPBRTH}</td>
            <td>${employee.STTBRTH}</td>
            <td>${employee.NATEMP}</td>
            <td>${employee.SOCSECNUM}</td>
            <td>${employee.BNKACCNUM}</td>
            <td>${employee.NATSERCRDNUM}</td>
            <td>${employee.NATIDNCRDNUM}</td>
            <td>${employee.CURRADDRS}</td>
            <td>${employee.PHONUM}</td>
            <td>${employee.EMLEMP}</td>
            <td>${employee.FAMSTT}</td>
            <td>${employee.HUSNM}</td>
            <td>${employee.HUSFMNM}</td>
            <td>${employee.NUMCHD}</td>
            <td>${employee.WORKREL}</td>
            <td>${employee.YRAPP}</td>

            <td>${employee.DEPNAME}</td>
            <td>${employee.SECNAME}</td>
            <td>${employee.RNKNAME}</td>
           <td>
              <button class="btn btn-secondary" onclick="printDes('${employee.EMPID}')"><i class="fa-solid fa-print"></i></button>
            </td>
           <td>
            <button class="btn btn-success" onclick="showUpdateFormEmp(
              '${employee.EMPID}',
              '${employee.FRSTNMEMP}',
              '${employee.LSTNMEMP}',
              '${employee.GNDEMP}',
              '${employee.EMPDATBRTH}',
              '${employee.MUNPBRTH}',
              '${employee.STTBRTH}',
              '${employee.SOCSECNUM}',
              '${employee.BNKACCNUM}',
              '${employee.NATSERCRDNUM}',
              '${employee.NATIDNCRDNUM}',
              '${employee.CURRADDRS}',
              '${employee.PHONUM}',
              '${employee.EMLEMP}',
              '${employee.FAMSTT}',
              '${employee.HUSNM}',
              '${employee.HUSFMNM}',
              '${employee.NUMCHD}',
              '${employee.WORKREL}',
              '${employee.DEPID}',
              '${employee.SECID}',
              '${employee.RNKID}',
              '${employee.RNKNAME}',
              )"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="getIDEmp('${employee.EMPID}')"><i class="fa-solid fa-trash"></i></button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.send();
}
inputSearch.addEventListener("input", searshEmployees);

// read employees
let employeesData = []; // تخزين بيانات الموظفين لاستخدامها في الفلترة

function readEmployees() {
  var xhr = new XMLHttpRequest();

  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_employees.php?action=read",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      employeesData = response.employees; // تخزين البيانات لاستخدامها في الفلترة
      displayEmployees(employeesData); // عرض البيانات في الجدول
    }
  };
  xhr.send();
}

function displayEmployees(data) {
  const tableBody = document.querySelector(".table-employees tbody");
  tableBody.innerHTML = ""; // مسح الجدول قبل عرض البيانات الجديدة

  data.forEach((employee) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${employee.EMPID}</td>
      <td>${employee.FRSTNMEMP}</td>
      <td>${employee.LSTNMEMP}</td>
      <td>${employee.GNDEMP}</td>
      <td>${employee.EMPDATBRTH}</td>
      <td>${employee.MUNPBRTH}</td>
      <td>${employee.STTBRTH}</td>
      <td>${employee.NATEMP}</td>
      <td>${employee.SOCSECNUM}</td>
      <td>${employee.BNKACCNUM}</td>
      <td>${employee.NATSERCRDNUM}</td>
      <td>${employee.NATIDNCRDNUM}</td>
      <td>${employee.CURRADDRS}</td>
      <td>${employee.PHONUM}</td>
      <td>${employee.EMLEMP}</td>
      <td>${employee.FAMSTT}</td>
      <td>${employee.HUSNM}</td>
      <td>${employee.HUSFMNM}</td>
      <td>${employee.NUMCHD}</td>
      <td>${employee.WORKREL}</td>
      <td>${employee.YRAPP}</td>
      <td>${employee.DEPNAME}</td>
      <td>${employee.SECNAME}</td>
      <td>${employee.RNKNAME}</td>
      <td>
        <button class="btn btn-secondary" onclick="printDes('${employee.EMPID}')"><i class="fa-solid fa-print"></i></button>
      </td>
      <td>
        <button class="btn btn-success" onclick="showUpdateFormEmp(
          '${employee.EMPID}',
          '${employee.FRSTNMEMP}',
          '${employee.LSTNMEMP}',
          '${employee.GNDEMP}',
          '${employee.EMPDATBRTH}',
          '${employee.MUNPBRTH}',
          '${employee.STTBRTH}',
          '${employee.SOCSECNUM}',
          '${employee.BNKACCNUM}',
          '${employee.NATSERCRDNUM}',
          '${employee.NATIDNCRDNUM}',
          '${employee.CURRADDRS}',
          '${employee.PHONUM}',
          '${employee.EMLEMP}',
          '${employee.FAMSTT}',
          '${employee.HUSNM}',
          '${employee.HUSFMNM}',
          '${employee.NUMCHD}',
          '${employee.WORKREL}',
          '${employee.DEPID}',
          '${employee.SECID}',
          '${employee.RNKID}',
          '${employee.RNKNAME}',

        )"><i class="fa-solid fa-pen-to-square"></i></button>
      </td>
      <td>
        <button class="btn btn-danger" onclick="getIDEmp('${employee.EMPID}')"><i class="fa-solid fa-trash"></i></button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

readEmployees();

//  دالة الفلترة
function filterEmployees() {
  let gender = document.getElementById("gender").value;
  let dept = document.getElementById("dept").value;
  let sector = document.getElementById("sctr").value;
  let rank = document.getElementById("rnk").value;

  let filteredData = employeesData.filter((employee) => {
    return (
      (gender === "" || employee.GNDEMP === gender) &&
      (dept === "" || employee.DEPID === dept) &&
      (sector === "" || employee.SECID === sector) &&
      (rank === "" || employee.RNKID === rank)
    );
  });

  displayEmployees(filteredData); // تحديث الجدول بالنتائج المفلترة
}

//  تحديث الجدول تلقائيًا عند تغيير القيم في الفلاتر
document.getElementById("gender").addEventListener("change", filterEmployees);
document.getElementById("dept").addEventListener("change", filterEmployees);
document.getElementById("sctr").addEventListener("change", filterEmployees);
document.getElementById("rnk").addEventListener("change", filterEmployees);

let sectorFilter = document.querySelector(".filteBox .sctr");
let ranksFilter = document.querySelector(".filteBox .rnk");
sectorFilter.addEventListener("change", function () {
  var sectorId = this.value;

  // إذا لم يتم اختيار قطاع، أفرغ القائمة
  if (sectorId === "") {
    ranksFilter.innerHTML = '<option value="">Select Rank</option>';
    return;
  }

  // إرسال الطلب إلى الخادم باستخدام AJAX
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "http://localhost/app%20EMS/api/api_employees.php?action=sector",
    true
  );
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      ranksFilter.innerHTML = xhr.responseText;
    }
  };
  xhr.send("sectorId=" + sectorId);
});

const sectorsAdd = document.querySelector(".model-add-employees .sctr");
const ranksAdd = document.querySelector(".model-add-employees .rnk");

sectorsAdd.addEventListener("change", function () {
  var sectorId = this.value;

  // إذا لم يتم اختيار قطاع، أفرغ القائمة
  if (sectorId === "") {
    ranksAdd.innerHTML = '<option value="">Select Rank</option>';
    return;
  }

  // إرسال الطلب إلى الخادم باستخدام AJAX
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "http://localhost/app%20EMS/api/api_employees.php?action=sector",
    true
  );
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      ranksAdd.innerHTML = xhr.responseText;
    }
  };
  xhr.send("sectorId=" + sectorId); // إرسال sectorId إلى الخادم
});
const sectorsUpdate = document.querySelector(".model-update-employees .sctr");
const ranksUpdate = document.querySelector(".model-update-employees .rnk");
sectorsUpdate.addEventListener("change", function () {
  var sectorIdUpdate = this.value;

  // إذا لم يتم اختيار قطاع، أفرغ القائمة
  if (sectorIdUpdate === "") {
    ranksUpdate.innerHTML = '<option value="">Select Rank</option>';
    return;
  }

  // إرسال الطلب إلى الخادم باستخدام AJAX
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "http://localhost/app%20EMS/api/api_employees.php?action=sector",
    true
  ); // استدعاء ملف PHP
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.status === 200) {
      ranksUpdate.innerHTML = xhr.responseText;
    }
  };
  xhr.send("sectorId=" + sectorIdUpdate);
});

function formatDate(inputDate) {
  const date = new Date(inputDate);
  const options = { day: "2-digit", month: "short", year: "2-digit" };
  return date.toLocaleDateString("en-GB", options).toUpperCase();
}
// This function adds a new employee to the system.
function funAddEmployee(event) {
  event.preventDefault();
  const fNm = document.getElementById("fNm").value;

  const lNm = document.getElementById("lNm").value;
  const datBrth = formatDate(document.getElementById("datBrth").value);

  const muncpBrth = document.getElementById("muncpBrth").value;
  const sttBrth = document.getElementById("sttBrth").value;
  const idCrdNum = document.getElementById("idCrdNum").value;
  const serCrdNum = document.getElementById("serCrdNum").value;
  const bnkAccNum = document.getElementById("bnkAccNum").value;
  const socSecNum = document.getElementById("socSecNum").value;
  const gnd = document.querySelector('input[name="gnd"]:checked')?.value;

  const addrs = document.getElementById("addrs").value;
  const eml = document.getElementById("eml").value;
  const phn = document.getElementById("phn").value;

  const maritalStatus = document.getElementById("maritalStatus").value;
  const husbNm = document.getElementById("husbNm").value;
  const husFmlyNm = document.getElementById("husFmlyNm").value;
  const numChld = document.getElementById("numChld").value;
  const sctr = document.querySelector(".model-add-employees .sctr").value;
  const rnk = document.querySelector(".model-add-employees .rnk").value;
  const dept = document.querySelector(".model-add-employees .dept").value;
  const wrkRlt = document.getElementById("wrkRlt").value;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      let message = document.getElementById("err_msg");
      if (response.status == "error") {
        message.textContent = response.message;
      }
      if (response.status === "success") {
        const message = document.querySelector(".alert-success .text");
        alertSuccess.style.display = "flex";
        message.textContent = response.message;
        readEmployees();
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_employees.php?action=insert&fNm=${fNm}&lNm=${lNm}&datBrth=${datBrth}&muncpBrth=${muncpBrth}&sttBrth=${sttBrth}&idCrdNum=${idCrdNum}&serCrdNum=${serCrdNum}&bnkAccNum=${bnkAccNum}&socSecNum=${socSecNum}&gnd=${gnd}&addrs=${addrs}&eml=${eml}&phn=${phn}&maritalStatus=${maritalStatus}&husbNm=${husbNm}&husFmlyNm=${husFmlyNm}&numChld=${numChld}&sctr=${sctr}&rnk=${rnk}&dept=${dept}&wrkRlt=${wrkRlt}`,
    true
  );
  xhr.send();
}

btnAddEmployee.addEventListener("click", funAddEmployee);

// start update

updateClose.onclick = () => {
  modelUpdateEmployees.style.display = "none";
};

function showUpdateFormEmp(
  id,
  fNm,
  lNm,
  gnd,
  dat,
  munp,
  stt,
  soc,
  bncAcc,
  natSec,
  natIdSec,
  addrss,
  phone,
  eml,
  marStt,
  husNm,
  husFNm,
  numChild,
  workRel,
  dept,
  sect,
  rnk,
  option
) {
  document.getElementById("idEmpUdate").value = id;
  document.getElementById("fNmEmpUdate").value = fNm;
  document.getElementById("lNmEmpUdate").value = lNm;

  // تحويل تاريخ الميلاد
  const dateParts = dat.split("-");
  const formattedDate = `${dateParts[2]}-${
    dateParts[1]
  }-${dateParts[0].padStart(2, "0")}`;
  document.getElementById("datBrthEmpUdate").value = formattedDate;

  document.getElementById("muncpBrthEmpUdate").value = munp;
  document.getElementById("sttBrthEmpUdate").value = stt;
  document.getElementById("idCrdNumEmpUdate").value = natIdSec;
  document.getElementById("serCrdNumEmpUdate").value =
    natSec === "null" ? "" : natSec;
  document.getElementById("bnkAccNumEmpUdate").value = bncAcc;
  document.getElementById("socSecNumEmpUdate").value = soc;

  document.getElementById(
    gnd === "M" ? "MaleEmpUdate" : "femaleEmpUdate"
  ).checked = true;

  document.getElementById("addrsEmpUpdate").value = addrss;
  document.getElementById("emlEmpUpdate").value = eml;
  document.getElementById("phnEmpUpdate").value = phone;

  document.getElementById("maritalStatusEmpUdate").value = marStt;
  document.getElementById("husbNmEmpUdate").value =
    husNm === "null" ? "" : husNm;
  document.getElementById("husFmlyNmEmpUdate").value =
    husFNm === "null" ? "" : husFNm;
  document.getElementById("numChldEmpUdate").value = numChild;

  // تعبئة السلك
  if (sect) {
    const sectorElement = document.getElementById("sctrEmpUpdate");
    if (sectorElement.querySelector(`option[value="${sect}"]`)) {
      sectorElement.value = sect;
    } else {
      console.log(`Sector value "${sect}" does not match any option.`);
    }
  }
  // تعبئة القسم
  if (dept) {
    const departmentElement = document.getElementById("deptEmpUpdate");
    if (departmentElement.querySelector(`option[value="${dept}"]`)) {
      departmentElement.value = dept;
    } else {
      console.warn(`Department value "${dept}" does not match any option.`);
    }
  }

  document.getElementById("wrkRltEmpUpdate").value = workRel;

  //  جلب الرتب بناءً على السلك وتعبئتها تلقائيًا
  fetchRanks(sect, rnk);

  document.querySelector(".model-update-employees").style.display = "block";
}

//  دالة جلب الرتب وتحديث القائمة المنسدلة
function fetchRanks(sectID, selectedRank) {
  if (!sectID) {
    document.getElementById("rnkEmpUpdate").innerHTML =
      '<option value=""> Please select the wire first </option>';
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_employees.php?action=rank&secID=${encodeURIComponent(
      sectID
    )}`,
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const rankElement = document.getElementById("rnkEmpUpdate");
      rankElement.innerHTML = xhr.responseText;
      if (
        selectedRank &&
        rankElement.querySelector(`option[value="${selectedRank}"]`)
      ) {
        rankElement.value = selectedRank;
      } else {
        console.warn(`Rank value "${selectedRank}" does not match any option.`);
      }
    }
  };

  xhr.send();
}

window.showUpdateFormEmp = showUpdateFormEmp;

function funUpdateEmployee() {
  let id = document.getElementById("idEmpUdate").value;
  let fNm = document.getElementById("fNmEmpUdate").value;
  let lNm = document.getElementById("lNmEmpUdate").value;

  let datBrth = document.getElementById("datBrthEmpUdate").value;
  let muncpBrth = document.getElementById("muncpBrthEmpUdate").value;
  let sttBrth = document.getElementById("sttBrthEmpUdate").value;
  let idCrdNum = document.getElementById("idCrdNumEmpUdate").value;
  let serCrdNum = document.getElementById("serCrdNumEmpUdate").value;
  let bnkAcc = document.getElementById("bnkAccNumEmpUdate").value;
  let socSec = document.getElementById("socSecNumEmpUdate").value;
  let gnd = document.querySelector('input[name="gndEmpUdate"]:checked')?.value;
  let addrs = document.getElementById("addrsEmpUpdate").value;
  let eml = document.getElementById("emlEmpUpdate").value;
  let phn = document.getElementById("phnEmpUpdate").value;
  let fStt = document.getElementById("maritalStatusEmpUdate").value;
  let husNm = document.getElementById("husbNmEmpUdate").value;
  let husFNm = document.getElementById("husFmlyNmEmpUdate").value;
  let numchld = document.getElementById("numChldEmpUdate").value;
  let sctr = document.getElementById("sctrEmpUpdate").value;
  let rnk = document.getElementById("rnkEmpUpdate").value;
  let dept = document.getElementById("deptEmpUpdate").value;
  let wrkRlt = document.getElementById("wrkRltEmpUpdate").value;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      console.log(response);
      let message = document.getElementById("err_msg");
      if (response.statusInput == "error") {
        message.textContent = response.messageInput;
      }
      if (response.status === "success") {
        const message = document.querySelector(".alert-success .text");
        alertSuccess.style.display = "flex";
        message.textContent = response.message;
        readEmployees();
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_employees.php?action=update&id=${id}&fNm=${fNm}&lNm=${lNm}&datBrth=${datBrth}&muncpBrth=${muncpBrth}&sttBrth=${sttBrth}&idCrdNum=${idCrdNum}&serCrdNum=${serCrdNum}&bnkAccNum=${bnkAcc}&socSecNum=${socSec}&gnd=${gnd}&addrs=${addrs}&eml=${eml}&phn=${phn}&maritalStatus=${fStt}&husbNm=${husNm}&husFmlyNm=${husFNm}&numChld=${numchld}&sctr=${sctr}&rnk=${rnk}&dept=${dept}&wrkRlt=${wrkRlt}`,
    true
  );
  xhr.send();
}
btnUpdateEmployee.onclick = funUpdateEmployee;

// end update

function DeleteEmpClose() {
  boxDeleteEmp.style.display = "none";
}
if (btnDeleteEmpClose) {
  btnDeleteEmpClose.onclick = DeleteEmpClose;
  btnDeleteEmpSecondary.onclick = DeleteEmpClose;
}

function getIDEmp(id) {
  empIdToDelete = id;
  empID.textContent = id;
  document.querySelector(".delete-Employee-box").style.display = "block";
  console.log(empIdToDelete);
}
window.getIDEmp = getIDEmp;

function deleteEmp() {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        const message = document.querySelector(".alert-success .text");
        alertSuccess.style.display = "flex";

        message.textContent = response.message;
        readEmployees();
        readCer();
      } else {
        const message = document.querySelector(".alert-warning .text");
        alertWarning.style.display = "flex";
        message.textContent = response.message;
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_employees.php?action=delete&id=${empIdToDelete}`,
    true
  );
  xhr.send();
}

btnDeleteEmp.addEventListener("click", function () {
  deleteEmp();
  boxDeleteEmp.style.display = "none";
});

function printDes(empID) {
  let id = empID;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_print_appointment.php?action=print&id=${id}`,
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
// Employee Certificate
function readCer() {
  let xhr = new XMLHttpRequest();

  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_employees.php?action=empCert",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-Certificate tbody");

      if (tableBody) {
        tableBody.innerHTML = ""; // مسح محتوى الجدول قبل إضافة البيانات الجديدة
        response.Certificates.forEach((cer) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${cer.CERID}</td>
            <td>${cer.EMPID}</td>
            <td>${cer.FULL_NAME}</td>
             <td>
              <button class="btn btn-secondary" onclick="printCertificate('${cer.CERID}')"><i class="fa-solid fa-print"></i></button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };

  xhr.send();
}
readCer();
function printCertificate(cerID) {
  let id = cerID;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_print-certificate.php?action=print&id=${id}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      // فتح ملف الـ PDF في نافذة جديدة
      var blob = new Blob([xhr.response], { type: "application/pdf" });
      var url = URL.createObjectURL(blob);
      window.open(url, "_blank");
    }
  };

  // تعيين استجابة البيانات على شكل blob (ملف PDF)
  xhr.responseType = "blob";

  xhr.send();
}
window.printCertificate = printCertificate;

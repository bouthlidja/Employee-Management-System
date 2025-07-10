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
// http://localhost/app%20EMS/api/api_report.php?action=reportAnnual&year
function reportAnnual() {
  let year = document.querySelector(".box .searsh").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status == "error") {
        document.querySelector(".reportAnnLev .alert-warning").style.display =
          "flex";
        document.querySelector(
          ".reportAnnLev .alert-warning .text"
        ).textContent = response.message;
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
              <td>${empNo.LVSID}</td>
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
// transfer report
console.log("report");

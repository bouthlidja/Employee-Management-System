let alertBox = document.querySelector("#dashboard .alert");
let alertText = document.querySelector("#dashboard .alert .text");

document.querySelector("#dashboard .alert .close").onclick = () => {
  document.querySelector("#dashboard .alert").style.display = "none";
};

function totalLogs() {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=totalLogs",
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        document.querySelector(".main-cards #totalLogs").textContent =
          response.count;
      }
    }
  };
  xhr.send();
}
setTimeout(totalLogs, 1000);

function todayLogs() {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=todayLogs",
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        document.querySelector(".main-cards #todayLogs").textContent =
          response.count;
      }
    }
  };
  xhr.send();
}
setTimeout(todayLogs, 1000);

function mostFrequentAction() {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=mostFrequentAction",
    true
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        document.querySelector(".main-cards #FrequentActionText").textContent =
          response.frequents[0]["ACTION_TYPE"];
        document.querySelector(".main-cards #mostFrequentAction").textContent =
          response.frequents[0]["ACTION_COUNT"];
      }
      console.log(response);
    }
  };
  xhr.send();
}
setTimeout(mostFrequentAction, 1000);

function read() {
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-logs tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.logs.forEach((log) => {
          const row = document.createElement("tr");
          row.innerHTML = `
          <td><input type="checkbox" class="row-checkbox" value="${log.LOGID}"></td>
            <td>${log.LOGID}</td>
            <td>${log.USRID}</td>
            <td>${log.ACTION_TYPE}</td>
            <td>${log.ACTION_TIME}</td>
            <td>${log.IP_ADDRESS}</td> 
            `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=select",
    true
  );
  xhr.send();
}
read();

document.getElementById("selectAll").addEventListener("change", function () {
  document.querySelectorAll(".row-checkbox").forEach((checkbox) => {
    checkbox.checked = this.checked;
  });
});

document
  .getElementById("deleteSelected")
  .addEventListener("click", function () {
    let selectedRows = [];
    document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
      selectedRows.push(checkbox.value);
    });

    let xhr = new XMLHttpRequest();
    xhr.open(
      "POST", // تغيير الطلب إلى POST
      "http://localhost/app%20EMS/api/api_dashboard.php?action=deleteSelected",
      true
    );
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        console.log(response);
        if (response.status === "success") {
          alertBox.classList.add("alert-success");
          alertBox.style.display = "flex";
          alertText.textContent = response.message;

          totalLogs();
          todayLogs();
          mostFrequentAction();
          read();
        } else {
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

    // إرسال البيانات بتنسيق JSON
    xhr.send(JSON.stringify({ logIDs: selectedRows }));
  });

document.querySelector("#showAll").addEventListener("click", function () {
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-logs tbody");
      tableBody.innerHTML = "";
      if (tableBody) {
        tableBody.innerHTML = "";
        response.logs.forEach((log) => {
          const row = document.createElement("tr");
          row.innerHTML = `
          <td><input type="checkbox" class="row-checkbox" value="${log.LOGID}"></td>
            <td>${log.LOGID}</td>
            <td>${log.USRID}</td>
            <td>${log.ACTION_TYPE}</td>
            <td>${log.ACTION_TIME}</td>
            <td>${log.IP_ADDRESS}</td> 
            `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=showAll",
    true
  );
  xhr.send();
});

document.querySelector("#show").onclick = read;

function searchLogs() {
  let val = document.querySelector("#searchInput").value;
  let xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-logs tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.logs.forEach((log) => {
          const row = document.createElement("tr");
          row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox" value="${log.LOGID}"></td>
          <td>${log.LOGID}</td>
          <td>${log.USRID}</td>
          <td>${log.ACTION_TYPE}</td>
          <td>${log.ACTION_TIME}</td>
          <td>${log.IP_ADDRESS}</td> 
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_dashboard.php?action=search&val=${val}`,
    true
  );
  xhr.send();
}

document.querySelector("#searchInput").addEventListener("input", searchLogs);

function loadChartData() {
  fetch("http://localhost/app%20EMS/api/api_dashboard.php?action=logsPerDay")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        let days = data.logs.map((item) => item.day);
        let counts = data.logs.map((item) => item.count);

        let ctx = document.getElementById("logsPerDay").getContext("2d");
        new Chart(ctx, {
          type: "bar",
          data: {
            labels: days,
            datasets: [
              {
                label: "Number of Actions Per Day",
                data: counts,
                backgroundColor: "rgba(54, 162, 235, 0.6)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
}

window.onload = loadChartData;

function loadOperationDistribution() {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_dashboard.php?action=operationDistribution",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        const operations = response.operations;
        const labels = operations.map((op) => op.ACTION_TYPE);
        const data = operations.map((op) => op.PERCENTAGE);

        // إنشاء المخطط
        const ctx = document
          .getElementById("operationPieChart")
          .getContext("2d");
        new Chart(ctx, {
          type: "pie",
          data: {
            labels: labels,
            datasets: [
              {
                label: "Percentage",
                data: data,
                backgroundColor: [
                  "#FF6384",
                  "#36A2EB",
                  "#FFCE56",
                  "#4CAF50",
                  "#8E44AD",
                ],
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: "top",
              },
            },
          },
        });
      }
    }
  };
  xhr.send();
}

// تحميل المخطط عند فتح الصفحة
loadOperationDistribution();

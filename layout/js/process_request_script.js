let alertBox = document.querySelector("#request .alert");
let alertText = document.querySelector("#request .alert .text");
document.querySelector("#model-process .close").onclick = () => {
  document.getElementById("model-process").style.display = "none";
};
function search() {
  let val = document.querySelector("#searchInput").value;
  var xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-req tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.request.forEach((req) => {
          const row = document.createElement("tr");
          row.innerHTML = `
              <td>${req.REQID}</td>
              <td>${req.EMPID}</td>
              <td>${req.FULL_NAME}</td>
              <td>${req.TYPREQ}</td>
              <td>${req.RSNREQ}</td>
              <td>${req.NOTREQ}</td>
              <td>${req.STTREQ}</td>

              <td>${req.CREATED_TIME}</td>

              <td>
                 <button class="btn btn-success" onclick="processRequest('${req.REQID}','${req.STTREQ}')">
                 
                 <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>
                 
                 </button>
              </td>

            `;
          tableBody.appendChild(row);
        });
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_process_request.php?action=search&val=${val}`,
    true
  );
  xhr.send();
}

document.querySelector("#searchInput").addEventListener("input", search);
let requestsData = [];
function read() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_process_request.php?action=select",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      requestsData = response.myRequest;
      display(requestsData);
    }
  };

  xhr.send();
}

//  عرض الطلبات في الجدول
function display(data) {
  const tableBody = document.querySelector(".table-req tbody");
  tableBody.innerHTML = ""; // مسح الجدول قبل إعادة الإضافة
  data.forEach((req) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${req.REQID}</td>
      <td>${req.EMPID}</td>
      <td>${req.FULL_NAME}</td>
      <td>${req.TYPREQ}</td>
      <td>${req.RSNREQ}</td>
      <td>${req.NOTREQ}</td>
      <td>${req.STTREQ}</td>
      <td>${req.CREATED_TIME}</td>
      <td>
        <button class="btn btn-success" onclick="processRequest('${req.REQID}','${req.STTREQ}')">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>
        </button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

//  الفلترة حسب الحالة
function filter() {
  let status = document.getElementById("reqSttFilter").value;

  let filteredData = requestsData.filter((req) => {
    return status === "" || req.STTREQ === status;
  });

  display(filteredData);
}

read();

//  تشغيل الفلترة عند تغيير الفلتر
document.getElementById("reqSttFilter").addEventListener("change", filter);

function processRequest(id, stt) {
  document.querySelector("#model-process #reqID").value = id;
  document.querySelector("#model-process #reqStt").value = stt;
  document.getElementById("model-process").style.display = "block";
}
window.processRequest = processRequest;

function update() {
  let id = document.querySelector("#model-process #reqID").value;
  let stt = document.querySelector("#model-process #reqStt").value;

  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    ` http://localhost/app%20EMS/api/api_process_request.php?action=update&reqID=${id}&reqstt=${stt}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.getElementById("model-process").style.display = "none";
        read();
      } else {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.getElementById("model-process").style.display = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };
  xhr.send();
}
window.update = update;

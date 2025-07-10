console.log("request_script.js");
let alertBox = document.querySelector("#request .alert");
let alertText = document.querySelector("#request .alert .text");

document.querySelector("#request .alert .close").onclick = () => {
  document.querySelector("#request .alert").style.display = "none";
};

document.querySelector(".btn-add-req").onclick = () => {
  document.querySelector(".model-add-req").style.display = "block";
};
document.querySelector(".model-add-req .close").onclick = () => {
  document.querySelector(".model-add-req").style.display = "none";
};

function read() {
  let usrID = document.querySelector("#usrIDSelect").value;
  // let usrID = 1243;
  var xhr = new XMLHttpRequest();

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-req tbody");
      if (tableBody) {
        tableBody.innerHTML = ""; // مسح محتوى الجدول قبل إضافة البيانات الجديدة
        response.myRequest.forEach((req) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${req.REQID}</td>
            <td>${req.TYPREQ}</td>
            <td>${req.RSNREQ}</td>
            <td>${req.NOTREQ}</td>
            <td>${req.STTREQ}</td>
         
            <td>${req.CREATED_TIME}</td>

            <td>
               <button class="btn btn-success" onclick="showUpdateForm('${req.REQID}','${req.USRID}','${req.TYPREQ}','${req.NOTREQ}','${req.RSNREQ}')"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="getReqID('${req.REQID}')"><i class="fa-solid fa-trash"></i></button>
            </td>

          `;
          tableBody.appendChild(row);
        });
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_request.php?action=select&usrID=${usrID}`,
    true
  );
  xhr.send();
}
read();

setTimeout(read, 3000);

function search() {
  let usrID = document.querySelector("#usrIDSelect").value;
  let val = document.querySelector("#searchInput").value;
  let xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-req tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.myRequest.forEach((req) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${req.REQID}</td>
            <td>${req.TYPREQ}</td>
            <td>${req.RSNREQ}</td>
            <td>${req.NOTREQ}</td>
            <td>${req.STTREQ}</td>

            <td>${req.CREATED_TIME}</td>

           <td>
               <button class="btn btn-success" onclick="showUpdateForm('${req.REQID}','${req.USRID}','${req.TYPREQ}','${req.NOTREQ}','${req.RSNREQ}')"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="getReqID('${req.REQID}')"><i class="fa-solid fa-trash"></i></button>
            </td>

          `;
          tableBody.appendChild(row);
        });
      }
    }
  };

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_request.php?action=search&usrID=${usrID}&val=${val}`,
    true
  );
  xhr.send();
}
document.querySelector("#searchInput").addEventListener("input", search);
function add() {
  let formData = new FormData(); // Create FormData to gather data and files

  formData.append("action", "insert"); // Send the requested action
  formData.append("usrID", document.querySelector("#usrID").value);
  formData.append("empID", document.querySelector("#empID").value);
  formData.append("typReq", document.querySelector("#typReq").value);
  formData.append("rsnReq", document.querySelector("#rsnReq").value);
  formData.append("notReq", document.querySelector("#notReq").value);

  // Get the file from input[type="file"]
  let fileInput = document.querySelector("#file");
  if (fileInput.files.length > 0) {
    formData.append("attachment", fileInput.files[0]); // Add the file to the request
  }

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/app%20EMS/api/api_request.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector("#typReq").value = "";
        document.querySelector("#rsnReq").value = "";
        document.querySelector("#notReq").value = "";
        document.querySelector(".model-add-req").style.display = "none";
        read();
      } else if (response.status === "error") {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector(".model-add-req").style.display = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };

  xhr.send(formData); // Send the data with the file
}
window.add = add;

function showUpdateForm(id, usrID, typ, not, rsn) {
  // تعبئة الحقول بالقيم الحالية للمستخدم
  document.getElementById("reqIDUpd").value = id; // ID المستخدم
  document.getElementById("usrIDUpd").value = usrID; // ID المستخدم
  document.getElementById("typReqUpd").value = typ;
  document.getElementById("rsnReqUpd").value = rsn;
  document.getElementById("notReqUpd").value = not;

  // إظهار النموذج
  document.getElementById("model-update").style.display = "block";
}

window.showUpdateForm = showUpdateForm;
document.querySelector(".model-update-req .close").onclick = () => {
  document.querySelector(".model-update-req").style.display = "none";
};

function update() {
  let formData = new FormData(); // إنشاء FormData لتجميع البيانات والملفات

  formData.append("action", "update"); // إرسال الإجراء المطلوب
  formData.append("reqID", document.querySelector("#reqIDUpd").value); // مهم جدًا لتحديد الطلب
  formData.append("usrID", document.querySelector("#usrIDUpd").value);
  formData.append("empID", document.querySelector("#empIDUpd").value);
  formData.append("typReq", document.querySelector("#typReqUpd").value);
  formData.append("rsnReq", document.querySelector("#rsnReqUpd").value);
  formData.append("notReq", document.querySelector("#notReqUpd").value);

  // ✅ التحقق مما إذا كان هناك ملف جديد مرفوع
  let fileInput = document.querySelector("#fileUpd");
  if (fileInput.files.length > 0) {
    formData.append("attachment", fileInput.files[0]); // إضافة الملف إلى الطلب
  }

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/app%20EMS/api/api_request.php", true);

  // ✅ التعامل مع الاستجابة
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector("#typReq").value = "";
        document.querySelector("#rsnReq").value = "";
        document.querySelector("#notReq").value = "";
        document.querySelector(".model-update-req").style.display = "none";
        read();
      } else if (response.status === "error") {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector(".model-update-req").style.display = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };

  xhr.send(formData); // إرسال البيانات مع الملف
}

window.update = update;

let reqIDToDelete = null;
function getReqID(id) {
  reqIDToDelete = id;
  document.querySelector(".model-delete-req .reqID").textContent = id;
  document.querySelector(".model-delete-req").style.display = "block";
}
window.getReqID = getReqID;
document.querySelector(".model-delete-req .close").onclick = () => {
  document.querySelector(".model-delete-req").style.display = "none";
};

function deleterequest() {
  const reqID = document.querySelector(".model-delete-req .reqID").textContent;

  // إرسال البيانات عبر AJAX
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector("#typReq").value = "";
        document.querySelector("#rsnReq").value = "";
        document.querySelector("#notReq").value = "";
        document.querySelector(".model-delete-req").style.display = "none";
        read();
      } else if (response.status === "error") {
        alertBox.classList.add("alert-warning");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        document.querySelector(".model-delete-req").style.display = "none";
      }
      setTimeout(() => {
        alertBox.style.display = "none";
        alertBox.classList.remove("alert-success", "alert-warning");
      }, 3000);
    }
  };
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_request.php?action=delete&id=${reqID}`,
    true
  );
  xhr.send();
}
document.querySelector(".model-delete-req .btn-delete-req").onclick =
  deleterequest;

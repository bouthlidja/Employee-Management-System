let alertBox = document.querySelector("#user .alert");
let alertText = document.querySelector("#user .alert .text");

document.querySelector("#user .alert .close").onclick = () => {
  document.querySelector("#user .alert").style.display = "none";
};

const btnAddUsers = document.querySelector(".btn-add-users");
const addUsers = document.querySelector(".add-users");
const btnAddClose = document.querySelector(".btn-add-close");

if (btnAddUsers && addUsers && btnAddClose) {
  btnAddUsers.onclick = () => {
    addUsers.style.display = "block";
  };
  btnAddClose.onclick = () => {
    addUsers.style.display = "none";
  };
}
// searsh users function
const btnSearshUsr = document.querySelector(".form-searsh .btn");
function searshUser() {
  const id = document.querySelector(".form-searsh .searsh").value;
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_user.php?action=searsh&usrID=${id}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      const tableBody = document.querySelector(".table-user tbody");
      console.log(response);
      if (tableBody) {
        tableBody.innerHTML = "";
        response.users.forEach((user) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${user.USRID}</td>
            <td>${user.USERNAME}</td>
            <td>${user.USREML}</td>
            <td>${user.USRPHN}</td>
            <td>${user.USRROLE}</td>
             <td>
               <button class="btn btn-success" onclick="showUpdateForm(${user.USRID}, '${user.USERNAME}', '${user.USREML}', '${user.USRPHN}', '${user.USRROLE}')"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="getIDUser(${user.USRID})"><i class="fa-solid fa-trash"></i></button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.send();
}
btnSearshUsr.onclick = searshUser;
// read users
function readUsers() {
  var xhr = new XMLHttpRequest();

  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_user.php?action=read",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);

      const tableBody = document.querySelector(".table-user tbody");
      if (tableBody) {
        tableBody.innerHTML = "";
        response.users.forEach((user) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${user.USRID}</td>
            <td>${user.USERNAME}</td>
            <td>${user.USREML}</td>
            <td>${user.USRPHN}</td>
            <td>${user.USRROLE}</td>
            <td>
               <button class="btn btn-success" onclick="showUpdateForm(${user.USRID}, '${user.USERNAME}', '${user.USREML}', '${user.USRPHN}', '${user.USRROLE}')"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
              <button class="btn btn-danger" onclick="getIDUser(${user.USRID})"><i class="fa-solid fa-trash"></i></button>
            </td>
          `;
          tableBody.appendChild(row);
        });
      }
    }
  };
  xhr.send();
}
readUsers();

// add new user
const btnAddUser = document.getElementById("btn-add-user");

function addUesr() {
  var username = document.getElementById("usrnm").value;
  var empID = document.getElementById("empID").value;
  var pass = document.getElementById("pass").value;
  var email = document.getElementById("eml").value;
  var phone = document.getElementById("phn").value;
  var role = document.getElementById("rl").value;
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readUsers();
        document.getElementById("usrnm").value = "";
        document.getElementById("empID").value = "";
        document.getElementById("pass").value = "";
        document.getElementById("eml").value = "";
        document.getElementById("phn").value = "";
        document.getElementById("rl").value = "";
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

  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_user.php?action=insert&username=${username}&empID=${empID}&pass=${pass}&email=${email}&phone=${phone}&role=${role}`,
    true
  );
  xhr.send();
}
if (btnAddUser) {
  btnAddUser.addEventListener("click", addUesr);
  btnAddUser.addEventListener("click", function () {
    addUsers.style.display = "none";
  });
}

// apdete page

const updateUsers = document.querySelector(".update-users");
const btnUpdateClose = document.querySelector(".btn-update-close");

if (addUsers && btnUpdateClose) {
  btnUpdateClose.onclick = () => {
    updateUsers.style.display = "none";
  };
}

function showUpdateForm(id, username, email, phone, role) {
  // تعبئة الحقول بالقيم الحالية للمستخدم
  document.getElementById("updateUsrId").value = id; // ID المستخدم
  document.getElementById("updateUsrnm").value = username;
  document.getElementById("updateUsrEml").value = email;
  document.getElementById("updateUsrPhn").value = phone;
  document.getElementById("updateUsrRrl").value = role;

  // إظهار النموذج
  document.querySelector(".update-users").style.display = "block";
}

window.showUpdateForm = showUpdateForm;
const btnUpdateUser = document.getElementById("btn-updata-user");

function updateUesr() {
  var usrID = document.getElementById("updateUsrId").value;
  var username = document.getElementById("updateUsrnm").value;
  // var pass = document.getElementById("updateUsrPass").value;
  var email = document.getElementById("updateUsrEml").value;
  var phone = document.getElementById("updateUsrPhn").value;
  var role = document.getElementById("updateUsrRrl").value;

  // إرسال البيانات عبر AJAX
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        // استدعاء دالة تحديث الجدول مباشرة
        readUsers();
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

  // إرسال طلب إضافة المستخدم إلى الخادم
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_user.php?action=update&id=${usrID}&username=${username}&email=${email}&phone=${phone}&role=${role}`,
    true
  );
  xhr.send();
}

if (btnUpdateUser) {
  btnUpdateUser.addEventListener("click", updateUesr);
  btnUpdateUser.addEventListener("click", function () {
    updateUsers.style.display = "none";
  });
}

//delete user
const btnDeleteClose = document.querySelector(".delete-user-box .close");
const boxDeleteUser = document.querySelector(".delete-user-box");
const btnDeleteSecondary = document.querySelector(
  ".delete-user-box .btn-secondary"
);

function DeleteClose() {
  boxDeleteUser.style.display = "none";
}
if (btnDeleteClose) {
  btnDeleteClose.onclick = DeleteClose;
  btnDeleteSecondary.onclick = DeleteClose;
}

const userID = document.querySelector(".userID");

let userIdToDelete = null; // متغير عالمي لتخزين المعرف
function getIDUser(id) {
  userIdToDelete = id; // تخزين المعرف في المتغير
  userID.textContent = id;
  document.querySelector(".delete-user-box").style.display = "block";
}
window.getIDUser = getIDUser;
const btnDeleteUser = document.querySelector(
  ".delete-user-box .btn-delete-user"
);
function deleteUser() {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);

      if (response.status === "success") {
        alertBox.classList.add("alert-success");
        alertBox.style.display = "flex";
        alertText.textContent = response.message;
        readUsers();
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
  xhr.open(
    "GET",
    `http://localhost/app%20EMS/api/api_user.php?action=delete&usrID=${userIdToDelete}`,
    true
  );
  xhr.send();
}
if (btnDeleteUser) {
  btnDeleteUser.addEventListener("click", function () {
    deleteUser();
    boxDeleteUser.style.display = "none";
  });
}

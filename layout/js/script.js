// alert_______________________________
const alertWarning = document.querySelector(".alert-warning");
const btnAlertWarning = document.querySelector(
  ".alert-warning .btn-close-alert"
);

const alertSuccess = document.querySelector(".alert-success");
const btnalertSuccess = document.querySelector(
  ".alert-success .btn-close-alert"
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

//---------------------------------------------------------
const menuIcon = document.querySelector(".menu-icon");
const gridContainer = document.querySelector(".grid-container");

if (menuIcon && gridContainer) {
  menuIcon.onclick = () => {
    gridContainer.classList.toggle("sid");
  };
}
//_______________________________________

// notifaction

function notifactionRead() {
  let xhr = new XMLHttpRequest();

  xhr.open(
    "GET",
    "http://localhost/app%20EMS/api/api_notifications.php?action=select",
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      let notificationMenu = document.querySelector(
        ".notification .dropdown-menu"
      );
      let notificationCounter = document.querySelector(
        ".notification #notif-count"
      );
      if (response.status === "success") {
        notificationMenu.innerHTML = ""; // تفريغ القائمة قبل إعادة تحميلها

        if (response.notifications.length > 0) {
          notificationCounter.textContent = response.notifications.length; // تحديث العداد
          response.notifications.forEach((notif) => {
            let listItem = document.createElement("li");
            listItem.innerHTML = `<a href="#">${notif.MSG}</a>`;
            if (notif.STATUS == "Unread") {
              listItem.style.backgroundColor = "#eee";
            }
            //  إضافة حدث عند النقر لتغيير الحالة إلى "Read"
            listItem.addEventListener("click", function () {
              markNotificationAsRead(
                notif.NOTID,
                listItem,
                notificationCounter
              );
            });
            notificationMenu.appendChild(listItem);
          });
        } else {
          notificationCounter.textContent = "0";
          notificationMenu.innerHTML = `<li><a href="#">No new notifications.</a></li>`;
        }
      }
    }
  };

  xhr.send();
}

function markNotificationAsRead(notifID, listItem, notificationCounter) {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    `http://localhost/app%20EMS/api/api_notifications.php?action=update&notifID=${notifID}`,
    true
  );

  xhr.onload = function () {
    if (xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        // ✅ إزالة اللون الأحمر من الخلفية بعد التحديث
        listItem.style.backgroundColor = "";

        // ✅ تقليل العداد بعد قراءة الإشعار
        let currentCount = parseInt(notificationCounter.textContent);
        notificationCounter.textContent =
          currentCount > 0 ? currentCount - 1 : 0;
      }
    }
  };

  xhr.send();
}
// استدعاء الدالة عند تحميل الصفحة
notifactionRead();
setInterval(notifactionRead, 2000);

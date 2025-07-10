<?php
session_start();



if (isset($_SESSION['username'])) {
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<div class="notifications-container">
        <h2>notification</h2>
        <ul id="notifications-list"></ul>
    </div>



<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const notificationsList = document.getElementById('notifications-list');

    // استرداد الإشعارات من API
    function fetchNotifications() {
        fetch('api/api_notification.php')
            .then(response => response.json())
            .then(data => {
                notificationsList.innerHTML = ''; // تفريغ القائمة
                data.notifications.forEach(notification => {
                    const li = document.createElement('li');
                    li.className = `notification ${notification.status === 'Unread' ? 'unread' : 'read'}`;
                    li.innerHTML = `
                        <p>${notification.message}</p>
                        <small>${notification.created_at}</small>
                        <button onclick="markAsRead(${notification.notificationID})">وضع كمقروء</button>
                    `;
                    notificationsList.appendChild(li);
                });
            });
    }

    // تحديث حالة الإشعار كمقروء
    window.markAsRead = function (notificationID) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notificationID })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications();
            }
        });
    };

    fetchNotifications();
});

</script>
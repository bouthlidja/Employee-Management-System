<?php
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
    $action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 

if ($action == 'select') {

    $notifications = array();
    $sql ="SELECT * FROM T_Notifications WHERE status = 'Unread'";
    $stmt = oci_parse($conn, $sql);
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($notifications, $rows);  
        }
        $result["status"]= "success";
        $result['notifications'] = $notifications;  
    }
    
    echo json_encode($result);

}elseif($action == 'update'){
        $notifID = test_input($_GET['notifID']);

        $sql = "UPDATE T_Notifications SET status = 'Read' WHERE notID = :notifID";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':notifID', $notifID);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $result["status"]= "success";
            $result["message"]= "Notification marked as read";
        } else {
            $result["status"]= "error";
            $result["message"]= "Failed to update notification";
        }
        echo json_encode($result);
}


?>
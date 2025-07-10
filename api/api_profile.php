<?php
header('Content-Type: application/json');
include '../connect.php';
include "../includes/functions/functions.php";

$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 

if ($action === "update") {
    $id = test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   '';
    $usrNm = test_input(isset($_GET['usrNm']) )  ?  test_input($_GET['usrNm']) :   '';
    $eml = test_input(isset($_GET['eml']) )  ?  test_input($_GET['eml']) :   '';
    $pass = test_input(isset($_GET['pass']) )  ?  test_input($_GET['pass']) :   '';
    $phn = test_input(isset($_GET['phn']) )  ?  test_input($_GET['phn']) :   '';

    if (empty($id) || empty($usrNm) || empty($eml)  || empty($phn)) {
        $result["status"] = "error";
        $result["message"] = "Missing required fields.";
        echo json_encode($result);
        exit;
    }

    if (!empty($pass)) {
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE T_Users SET username = :usrNm, 
                                   psswrd = :pass, 
                                   usrEml = :eml, 
                                   usrPhn = :phn 
                                   WHERE usrID = :id";
    } else {
        $sql = "UPDATE T_Users SET username = :usrNm, 
                                   usrEml = :eml, 
                                   usrPhn = :phn 
                                   WHERE usrID = :id";
    }

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':usrNm', $usrNm);
    oci_bind_by_name($stmt, ':eml', $eml);
    oci_bind_by_name($stmt, ':phn', $phn);
    oci_bind_by_name($stmt, ':id', $id);
    
    // اربط كلمة المرور فقط إذا كانت غير فارغة
    if (!empty($pass)) {
        oci_bind_by_name($stmt, ':pass', $hashedPassword);
    }

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $result["status"] = "success";
        $result["message"] = "Profile updated successfully.";
        echo json_encode($result);
    } else {
        $result["status"] = "error";
        $result["message"] = "Profile update failed.";
        echo json_encode($result);
    }
}


?>
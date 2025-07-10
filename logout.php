<?php
session_start();
include "init.php"; // الاتصال بقاعدة البيانات

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
    $ip = $_SERVER['REMOTE_ADDR']; // عنوان IP المستخدم
    $actionType = "User Logout";

    // ✅ تسجيل الخروج في جدول `T_log_active`
    $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
               VALUES (SEQ_LOG.NEXTVAL, :usrID, :actionType, SYSTIMESTAMP, :ipAddress)";
    $stmtLog = oci_parse($conn, $logSQL);

    oci_bind_by_name($stmtLog, ':usrID', $userID);
    oci_bind_by_name($stmtLog, ':actionType', $actionType);
    oci_bind_by_name($stmtLog, ':ipAddress', $ip);

    oci_execute($stmtLog);
    oci_commit($conn);
    oci_free_statement($stmtLog);
}

// ✅ تدمير الجلسة
session_unset();
session_destroy();

// ✅ إعادة التوجيه إلى صفحة تسجيل الدخول
header('location: index.php');
exit();
?>

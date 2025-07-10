<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 

if($action == 'select'){
    $id  = isset($_GET['usrID']) ? $_GET['usrID'] : "";
    $myRequests = array();
    $sql = "    SELECT  
    r.reqID, 
    e.empID, 
    (e.frstNmEmp || ' ' || e.lstNmEmp) AS full_name,
    r.typReq,
    r.sttReq, 
    r.rsnReq, 
    r.notReq,  
    TO_CHAR(r.created_at, 'DD-MM-YYYY HH24:MI') AS created_time  
    FROM T_Requests r
    JOIN T_employees e ON e.empID = r.empID";
    $stmt = oci_parse($conn, $sql);
    if(oci_execute($stmt)){
      while($rows = oci_fetch_assoc($stmt)){
          array_push($myRequests, $rows);
      }
    $result['status'] = 'success';
    $result['myRequest'] = $myRequests;   
    } 
    echo json_encode($result);
    exit();
  
}
elseif ($action == 'update') {
    $reqID =  test_input($_GET["reqID"]) ; // رقم الطلب
    $reqstt =  test_input($_GET["reqstt"]) ; // رقم الطلب
    $sql = "UPDATE T_Requests  SET sttReq = :sttReq WHERE reqID = :reqID";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':reqID', $reqID);
    oci_bind_by_name($stmt, ':sttReq', $reqstt);

    if (oci_execute($stmt)) {
                $adminID =   $_SESSION['id'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'request process', SYSTIMESTAMP, :ipAddress)";
                $logStmt = oci_parse($conn, $logSQL);
                oci_bind_by_name($logStmt, ':adminID', $adminID);
                oci_bind_by_name($logStmt, ':ipAddress', $ip);
                oci_execute($logStmt);
                oci_commit($conn);
        $result["status"] = "success";
        $result["message"] = "Request updated successfully";
    } else {
        $error = oci_error();
        $result["status"] = "error";
        $result["message"] = "Request update failed: " . $error['message'];
    }
    
    echo json_encode($result);
}
elseif ($action == 'search'){
    $val = test_input(isset($_GET['val'])) ? test_input($_GET['val'] ): "";

    $requests = array();
    $sql = "SELECT  
            r.reqID, 
            e.empID, 
            (e.frstNmEmp || ' ' || e.lstNmEmp) AS full_name,
            r.typReq,
            r.sttReq, 
            r.rsnReq, 
            r.notReq,  
            TO_CHAR(r.created_at, 'DD-MM-YYYY HH24:MI') AS created_time 
            FROM T_Requests r
            JOIN T_employees e ON e.empID = r.empID
            WHERE r.reqID LIKE :searchValue || '%'
            OR e.empID  LIKE :searchValue || '%'
            OR (e.frstNmEmp || ' ' || e.lstNmEmp) LIKE :searchValue || '%' ";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':searchValue', $val);
    if(oci_execute($stmt)){
      while($rows = oci_fetch_assoc($stmt)){
          array_push($requests, $rows);
      }
    $result['status'] = 'success';
    $result['request'] = $requests;
  
    
    } 
    echo json_encode($result);
   
    exit();


}

?>
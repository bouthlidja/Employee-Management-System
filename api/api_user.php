<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

include "../includes/functions/functions.php";

$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 

$count = 0;

if ($action ==='countUser') 
{
    getCount($conn,'T_Users');
}
elseif ($action === "searsh"){

  $id  = isset($_GET['usrID']) ? $_GET['usrID'] : "";


  $users = array();
  $sql = "SELECT  *   FROM T_Users WHERE usrID = :paramID";
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ':paramID', $id);
  if(oci_execute($stmt)){
    while($rows = oci_fetch_assoc($stmt)){
        array_push($users, $rows);
    }
    $result['users'] = $users;
  }else{
    $e = oci_error();
    $result['error'] = 'not execute' . $e['message'];
  }
  echo json_encode($result);

}
elseif ($action === "read")
{  
  echo  selectAll($conn,"T_Users") ;
}elseif ($action === "insert"){
  $adminID =   $_SESSION['id'];
  $ip = $_SERVER['REMOTE_ADDR'];
  $username  = isset($_GET['username']) ? $_GET['username'] : "";
  $empID  = isset($_GET['empID']) ? $_GET['empID'] : "";
  $password  = isset($_GET['pass']) ? $_GET['pass'] : "";
  $email     = isset($_GET['email']) ? $_GET['email'] : "";
  $phone     = isset($_GET['phone']) ? $_GET['phone'] : "";
  $role      = isset($_GET['role']) ? $_GET['role'] : "";

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($empID) || empty($password) || empty($email) || empty($phone) || empty($role) ) {
      $result["status"]= "error";
      $result["message"]= "Missing required fields.";
      echo json_encode($result);
      exit;
    }

  $checkEmpSQL = "SELECT COUNT(EMPID) AS count FROM t_Users WHERE empID = :empID";
  $checkcheckEmpSTMT = oci_parse($conn, $checkEmpSQL);
  oci_bind_by_name($checkcheckEmpSTMT, ':empID', $empID);
  oci_execute($checkcheckEmpSTMT);
  $empRow = oci_fetch_assoc($checkcheckEmpSTMT);
  if($empRow['COUNT']> 0){
      $result["status"]= "error";
      $result["message"]=  "The employee already has an account.";
          echo json_encode($result);
          exit;
      }


    $insertSQL = "INSERT INTO T_Users (usrID, username,empID,psswrd,usrEml, usrPhn, usrRole) VALUES(SEQ_USR.nextval,  :paramUn, :paramempID, :paramPsswrd, :paramUsrEml, :paramUsrPhn, :paramUsrRole) ";
  $insertStmt = oci_parse($conn, $insertSQL);
  oci_bind_by_name($insertStmt, ':paramUn', $username);
  oci_bind_by_name($insertStmt, ':paramempID', $empID);
  oci_bind_by_name($insertStmt, ':paramPsswrd', $hashedPassword);
  oci_bind_by_name($insertStmt, ':paramUsrEml', $email);
  oci_bind_by_name($insertStmt, ':paramUsrPhn', $phone);
  oci_bind_by_name($insertStmt, ':paramUsrRole', $role);

  if (oci_execute($insertStmt)) {
    oci_commit($conn);

    $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                 VALUES (SEQ_LOG.NEXTVAL, :adminID, 'User Insert', SYSTIMESTAMP, :ipAddress)";
      $logStmt = oci_parse($conn, $logSQL);
      oci_bind_by_name($logStmt, ':adminID', $adminID);
      oci_bind_by_name($logStmt, ':ipAddress', $ip);
      oci_execute($logStmt);
      oci_commit($conn);
    

    $result["status"]= "success";
    $result["message"]= "User added successfully";

    echo json_encode($result);
  } else {
    $error = oci_error();
    $result["status"]= "error";
    $result["message"]= "User was not added successfully." . $error['message'];

    echo json_encode($result);
    
  }
}

elseif($action === 'update'){
  $id       = test_input($_GET['id']) ? intval($_GET['id']) : ""; 
  $username = test_input($_GET['username']) ? $_GET['username'] : "";
  $email    = test_input($_GET['email']) ? $_GET['email'] : "";
  $phone    = test_input($_GET['phone']) ? intval($_GET['phone']) : ""; 
  $role     = test_input($_GET['role']) ? $_GET['role'] : "";

  if (empty($id) || empty($username) || empty($email) || empty($phone) || empty($role)) {
    $result["status"] = "error";
    $result["message"] = "Missing required fields.";
    echo json_encode($result);
    exit;
  }

  $SQL = "UPDATE T_Users SET username = :paramUn,
                             usrEml = :paramUsrEml,
                             usrPhn = :paramUsrPhn,
                             usrRole = :paramUsrRole
                             WHERE usrID = :paramId";

  $stmt = oci_parse($conn, $SQL);
  oci_bind_by_name($stmt, ':paramUn', $username);
  oci_bind_by_name($stmt, ':paramUsrEml', $email);
  oci_bind_by_name($stmt, ':paramUsrPhn', $phone); 
  oci_bind_by_name($stmt, ':paramUsrRole', $role);
  oci_bind_by_name($stmt, ':paramId', $id);

  if (oci_execute($stmt)) {
      oci_commit($conn);
      //  تسجيل التحديث في T_log_active
      $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
      VALUES (SEQ_LOG.NEXTVAL, :paramId, 'User Update', SYSTIMESTAMP, :ipAddress)";
      $logStmt = oci_parse($conn, $logSQL);
      $ip = $_SERVER['REMOTE_ADDR'];
      oci_bind_by_name($logStmt, ':paramId', $id);
      oci_bind_by_name($logStmt, ':ipAddress', $ip);
      if (oci_execute($logStmt)) {
        oci_commit($conn);
       
      }
      $result["status"] = "success";
        $result["message"] = "User updated successfully";
  } else {
      $error = oci_error();
      $result["status"] = "error";
      $result["message"] = "User was not updated successfully. " . $error['message'];
  }

  echo json_encode($result);
 
}

elseif ($action === 'delete') {
  $adminID =   $_SESSION['id']; 
  $id  = isset($_GET['usrID']) ? $_GET['usrID'] : "";
  $ip = $_SERVER['REMOTE_ADDR'];
 
  $sqlCheck = "SELECT COUNT(*) AS CNT FROM t_users WHERE  usrID = :adminID";
  $stmtCheck = oci_parse($conn, $sqlCheck);
  oci_bind_by_name($stmtCheck, ':adminID', $adminID);
  oci_execute($stmtCheck);
  $row = oci_fetch_assoc($stmtCheck);

  if ($row['CNT'] > 0) {
      echo json_encode([
          "status" => "error",
          "message" => "You cannot delete your own record."
      ]);
      exit; 
  }
  //   1. حذف المستخدم أولاً
  $SQL = "DELETE FROM T_Users WHERE usrID = :usrID";
  $stmt = oci_parse($conn, $SQL);
  oci_bind_by_name($stmt, ':usrID', $id);

  if (oci_execute($stmt)) {
      oci_commit($conn);
    //  2. إدراج السجل في `T_log_active` بعد التأكد من نجاح الحذف
    $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'User Delete', SYSTIMESTAMP, :ipAddress )";
    
    $logStmt = oci_parse($conn, $logSQL);
    oci_bind_by_name($logStmt, ':adminID', $adminID); 
    oci_bind_by_name($logStmt, ':ipAddress', $ip);

    if (oci_execute($logStmt)) {
      oci_commit($conn);
      $result["status"]= "success";
      $result["message"]= "delete  user successfully";
    }   
  } 
  echo json_encode($result);    
}
?>

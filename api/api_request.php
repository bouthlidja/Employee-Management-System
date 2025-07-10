<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "insert") {
    $usrID =  test_input($_POST["usrID"]) ;
    $empID =  test_input($_POST["empID"]) ;
    $typReq = test_input($_POST["typReq"]) ;
    $rsnReq = test_input($_POST["rsnReq"]);
    $notReq = test_input($_POST["notReq"]) ;
    $filePath = null;
  // Check that all required fields have been received
  if (empty($usrID) || empty($empID) || empty($typReq) ||  empty($rsnReq) || empty($notReq)) {
    $result['status'] = 'error';
    $result['message'] = 'Missing required fields';
    echo json_encode($result);
    exit;
  }
    $uploadDir = "C:/xampp/htdocs/app EMS/uploads/Employees Requests/$empID/";  
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the folder with full permissions
    }
    // Handle file upload
    if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] == 0) {
        $uploadDir = "C:/xampp/htdocs/app EMS/uploads/Employees Requests/$empID/"; // The folder where the files will be saved
        $fileName = time() . "_" . basename($_FILES["attachment"]["name"]); // تغيير اسم الملف لضمان عدم التكرار
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $filePath)) {
            $filePath = $filePath; // Renaming the file to ensure it doesn't duplicate
        } else {
          
            $result["status"]= "error";
            $result["message"]= "Failed to upload file";
            echo json_encode($result);

            exit();
        }
    }
  //  Variable to store reqID after insertion
    $reqID = 0;
    // Inserting data into the database
    $sql = "INSERT INTO T_Requests (reqID, usrID, empID, typReq, rsnReq, notReq, file_path) 
            VALUES (SEQ_REQ.nextval, :usrID, :empID, :typReq, :rsnReq, :notReq, :filePath) 
            RETURNING reqID INTO :reqID";
    $stmt = oci_parse($conn, $sql);  
        oci_bind_by_name($stmt, ':usrID', $usrID);
        oci_bind_by_name($stmt, ':empID', $empID);
        oci_bind_by_name($stmt, ':typReq', $typReq);
        oci_bind_by_name($stmt, ':notReq', $notReq);
        oci_bind_by_name($stmt, ':rsnReq', $rsnReq); 
        oci_bind_by_name($stmt, ':filePath', $filePath); 
        oci_bind_by_name($stmt, ':reqID', $reqID, -1, SQLT_INT); // Fetching reqID after insertion

        if (oci_execute($stmt)) {
          oci_commit($conn);

          $adminID =   $_SESSION['id']; // The user who performs the deletion
          $ip = $_SERVER['REMOTE_ADDR'];
          $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
          VALUES (SEQ_LOG.NEXTVAL, :adminID, 'request insert', SYSTIMESTAMP, :ipAddress)";
          $logStmt = oci_parse($conn, $logSQL);
          oci_bind_by_name($logStmt, ':adminID', $adminID);
          oci_bind_by_name($logStmt, ':ipAddress', $ip);
          oci_execute($logStmt);
          oci_commit($conn);
          
          $result["status"]= "success";
          $result["message"]= "request added successfully";

          //____________________
          $sql = "SELECT (frstNmEmp ||' ' || lstNmEmp) AS full_name FROM T_employees WHERE empID = :empID";
          $stmt = oci_parse($conn, $sql);
          oci_bind_by_name($stmt, ":empID", $empID);
          oci_execute($stmt);
          $full_name = "";
          if ($row = oci_fetch_assoc($stmt)) {
              $full_name = $row["FULL_NAME"];
            }
    // Add a notification after the request is successful
    $notMsg = "A new request (ID: $reqID) has been sent from $full_name (Employee ID: $empID)";
    addNotification($conn, $usrID, $notMsg);
         
        } else {
          $error = oci_error();
          $result["status"]= "error";
          $result["message"]= "request was not added successfully." . $error['message'];
        }
          echo json_encode($result);
          exit;
}

if($action == 'select'){
  $id  = isset($_GET['usrID']) ? $_GET['usrID'] : "";

 
  $myRequests = array();
  $sql = "SELECT  reqID ,usrID, typReq, sttReq,rsnReq, notReq,  TO_CHAR(created_at, 'DD-MM-YYYY HH24:MI') AS created_time
   FROM T_Requests WHERE usrID = :paramID";
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ':paramID', $id);
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "update") {
  $reqID =  test_input($_POST["reqID"]) ; // رقم الطلب
  $usrID =  test_input($_POST["usrID"]) ;
  $empID =  test_input($_POST["empID"]) ;
  $typReq = test_input($_POST["typReq"]) ;
  $rsnReq = test_input($_POST["rsnReq"]);
  $notReq = test_input($_POST["notReq"]) ;
  if (empty($usrID) || empty($empID) || empty($typReq) ||  empty($rsnReq) || empty($notReq)) {
    $result['status'] = 'error';
    $result['message'] = 'Missing required fields';
    echo json_encode($result);
    exit;
  }
  $sql = "SELECT sttReq FROM T_Requests WHERE reqID = :reqID";
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ":reqID", $reqID);
  oci_execute($stmt);
  $status = "";
  if ($row = oci_fetch_assoc($stmt)) {
      $status = $row["STTREQ"];
      if($status == 'rejected' || $status == 'accepted'){
        $result["status"] = "error";
          $result["message"] = "You can no longer modify this request.";
          echo json_encode($result);
          exit();
      }
  }

  //  1 جلب الملف القديم من قاعدة البيانات
  $sql = "SELECT file_path FROM T_Requests WHERE reqID = :reqID";
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ":reqID", $reqID);
  oci_execute($stmt);
  $oldFile = "";
  if ($row = oci_fetch_assoc($stmt)) {
      $oldFile = $row["FILE_PATH"];
  }

  $filePath = $oldFile; // الاحتفاظ بالملف القديم إذا لم يتم رفع ملف جديد

  // 2 التحقق من وجود ملف جديد ورفعه
  if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] == 0) {
      $uploadDir = "C:/xampp/htdocs/app EMS/uploads/";
      $fileName = time() . "_" . basename($_FILES["attachment"]["name"]);
      $newFilePath = $uploadDir . $fileName;

      // 3 حذف الملف القديم إذا كان موجودًا
      if (!empty($oldFile) && file_exists($oldFile)) {
          unlink($oldFile); // حذف الملف القديم
      }

      // 4 نقل الملف الجديد إلى السيرفر
      if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $newFilePath)) {
          $filePath = $newFilePath; // تحديث مسار الملف الجديد
      } else {
          $result["status"] = "error";
          $result["message"] = "Failed to upload new file";
          echo json_encode($result);
          exit();
      }
  }

  // 5 تحديث البيانات في قاعدة البيانات
  $sql = "UPDATE T_Requests 
          SET usrID = :usrID, empID = :empID, typReq = :typReq, rsnReq = :rsnReq, 
              notReq = :notReq, file_path = :filePath 
          WHERE reqID = :reqID";
  
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ':usrID', $usrID);
  oci_bind_by_name($stmt, ':empID', $empID);
  oci_bind_by_name($stmt, ':typReq', $typReq);
  oci_bind_by_name($stmt, ':rsnReq', $rsnReq);
  oci_bind_by_name($stmt, ':notReq', $notReq);
  oci_bind_by_name($stmt, ':filePath', $filePath);
  oci_bind_by_name($stmt, ':reqID', $reqID);

  if (oci_execute($stmt)) {
      oci_commit($conn);
      $adminID =   $_SESSION['id']; // المستخدم الذي يقوم بالحذف
      $ip = $_SERVER['REMOTE_ADDR'];
      $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
      VALUES (SEQ_LOG.NEXTVAL, :adminID, 'update delete', SYSTIMESTAMP, :ipAddress)";
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
  exit;
}

if ($action == "delete") {
  $id = "";
  isset($_GET['id']) ? $id = $_GET['id'] : $id = "" ;


  $sql = "SELECT sttReq FROM T_Requests WHERE reqID = :reqID";
  $stmt = oci_parse($conn, $sql);
  oci_bind_by_name($stmt, ":reqID", $id);
  oci_execute($stmt);
  $status = "";
  if ($row = oci_fetch_assoc($stmt)) {
      $status = $row["STTREQ"];
      if($status == 'rejected' || $status == 'accepted'){
        $result["status"] = "error";
          $result["message"] = "You can no longer delete this request.";
      }else{
        $adminID =   $_SESSION['id']; // المستخدم الذي يقوم بالحذف
        $ip = $_SERVER['REMOTE_ADDR'];
        $SQL = "DELETE FROM T_Requests WHERE reqID = :reqID";
        $stmt = oci_parse($conn, $SQL);
        oci_bind_by_name($stmt, ':reqID', $id);
      
        if (oci_execute($stmt)) {
            oci_commit($conn);
             
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'request delete', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);
            
            $result["status"]= "success";
            $result["message"]= "delete  request successfully";
             
        } 
      }
  }
 
  echo json_encode($result);
}

if ($action == 'search') {
  $id  = test_input(isset($_GET['usrID'])) ? test_input($_GET['usrID'])  : "";
  $val = test_input(isset($_GET['val'])) ? test_input($_GET['val'] ): '';
  $myRequests = array();
  $sql = "SELECT  reqID,
                  usrID,
                  typReq,
                  sttReq,
                  rsnReq, 
                  notReq,  
                  TO_CHAR(created_at, 'DD-MM-YYYY HH24:MI') AS created_time
          FROM T_Requests WHERE usrID = :paramID
          AND reqID LIKE :searchValue || '%'";
  $stmt = oci_parse($conn, $sql);

  oci_bind_by_name($stmt, ':paramID', $id);
  oci_bind_by_name($stmt, ':searchValue', $val);

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

?>
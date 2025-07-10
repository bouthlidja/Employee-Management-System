<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include "../includes/functions/functions.php";

$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 

if ($action == "create")
{
    $id = isset($_GET['id']) ? test_input($_GET['id']) : '';
    
    if (empty($id)) {
        $result["status"] = "error";
        $result["message"] = "Missing required fields.";
        echo json_encode($result);
        exit;
    }

    //  استعلام للحصول على fldrID من SEQ_FLD.nextval
    $sqlSeq = "SELECT 'FLD-' || SEQ_FLD.nextval AS new_fldrID FROM dual";
    $stmtSeq = oci_parse($conn, $sqlSeq);
    oci_execute($stmtSeq);
    $row = oci_fetch_assoc($stmtSeq);
    $fldrID = $row['NEW_FLDRID'];

    //  إنشاء مسار المجلد بناءً على fldrID
    $folderPath = "C:\\xampp\\htdocs\\app EMS\\uploads\\Employees Folder\\" . $fldrID;

    //  إدراج البيانات في الجدول
    $sql = "INSERT INTO T_folders (fldrID, empID, fldr_path, created_at) 
            VALUES (:fldrID, :empID, :fldrPath, CURRENT_TIMESTAMP)";
    $stmt = oci_parse($conn, $sql);
    
    oci_bind_by_name($stmt, ':fldrID', $fldrID);
    oci_bind_by_name($stmt, ':empID', $id);
    oci_bind_by_name($stmt, ':fldrPath', $folderPath);

    if (oci_execute($stmt)) {
        $adminID =   $_SESSION['id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
        VALUES (SEQ_LOG.NEXTVAL, :adminID, 'create folder', SYSTIMESTAMP, :ipAddress)";
        $logStmt = oci_parse($conn, $logSQL);
        oci_bind_by_name($logStmt, ':adminID', $adminID);
        oci_bind_by_name($logStmt, ':ipAddress', $ip);
        oci_execute($logStmt);
        oci_commit($conn);

        // ✅ التحقق مما إذا كان المجلد موجودًا قبل إنشائه
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
            $result["status"] = "success";
            $result["message"] = "The folder was created successfully with ID: $fldrID.";
        } else {
            $result["status"] = "error";
            $result["message"] = "The folder already exists.";
        }
    } else {
        $error = oci_error($stmt);
        $result["status"] = "error";
        $result["message"] = "Database error: " . $error['message'];
    }

    echo json_encode($result);
}
elseif ($action ==="select")
{
    $folder = array();
   $sql ="SELECT    f.fldrid,
                    f.empid,
                    (e.frstNmEmp || ' ' || e.lstNmEmp) AS full_name 
         FROM t_folders f  
         JOIN  t_employees e ON f.empID = e.empID ORDER BY f.fldrid DESC";
    
    $stmt = oci_parse($conn, $sql);

    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($folder, $rows);
        }
        $result['folder'] = $folder;
    }else{
        $e = oci_error();
        $result['error'] = 'not execute' . $e['message'];
      }
      echo json_encode($result);


}
elseif($action === 'delete')
{
    $adminID =   $_SESSION['id']; // المستخدم الذي يقوم بالحذف
  $fldID  = isset($_GET['id']) ? $_GET['id'] : "";
  $ip = $_SERVER['REMOTE_ADDR'];
 

  //   1. حذف المستخدم أولاً
  $SQL = "DELETE FROM T_folders WHERE fldrID = :fldID";
  $stmt = oci_parse($conn, $SQL);
  oci_bind_by_name($stmt, ':fldID', $fldID);

  if (oci_execute($stmt)) {

    //  2. دراج السجل في `T_log_active` بعد التأكد من نجاح الحذف
    $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'folder Delete', SYSTIMESTAMP, :ipAddress )";
    
    $logStmt = oci_parse($conn, $logSQL);
    oci_bind_by_name($logStmt, ':adminID', $adminID); // المستخدم الذي قام بالحذف
    oci_bind_by_name($logStmt, ':ipAddress', $ip);

    if (oci_execute($logStmt)) {
      oci_commit($conn);
      $result["status"]= "success";
      $result["message"]= "delete  folder successfully";
    }   
  } 
  echo json_encode($result);


}
elseif ($action ==="upload")
{
    $folderID = $_POST["folderID"];
    $file = $_FILES["file"];

    if ($file["error"] == 0) {
        $file_name = basename($file["name"]);
        $upload_dir = "C:/xampp/htdocs/app EMS/uploads/Employees Folder/$folderID/";
        $file_path = $upload_dir . $file_name;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file["tmp_name"], $file_path)) {
            $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'upload file ' || :folderID, SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':folderID', $folderID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);
            echo json_encode(["status" => "success", "message" => "The document has been uploaded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to transfer the file to the server"]);
        }
    }
}
elseif($action === 'open')
{
    $folderID = $_GET["folderID"];
    $folderPath = "C:/xampp/htdocs/app EMS/uploads/Employees Folder/" . $folderID;
    
    if (is_dir($folderPath)) {
        // إنشاء رابط للوصول إلى المجلد من المتصفح
        $folderUrl = "http://localhost/app%20EMS/uploads/Employees%20Folder/" . urlencode($folderID);
        echo json_encode(["status" => "success", "path" => $folderUrl]);
    } else {
        echo json_encode(["status" => "error", "message" => "The folder does not exist."]);
    }
}
elseif($action === 'searshFld')
{
    $val = isset($_GET['val']) ? $_GET['val'] : "";
 
    $folder = array();
    $sql ="SELECT    f.fldrid,
                     f.empid,
                     (e.frstNmEmp || ' ' || e.lstNmEmp) AS full_name 
          FROM t_folders f  
          JOIN  t_employees e ON f.empID = e.empID WHERE f.fldrid LIKE :searchValue || '%'
            OR (e.frstNmEmp || ' ' || e.lstNmEmp) LIKE :searchValue || '%' ";
     
     $stmt = oci_parse($conn, $sql);
     oci_bind_by_name($stmt, ':searchValue', $val);
 
     if(oci_execute($stmt)){
         while($rows = oci_fetch_assoc($stmt)){
             array_push($folder, $rows);
         }
         $result['folder'] = $folder;
     }else{
         $e = oci_error();
         $result['error'] = 'not execute' . $e['message'];
       }
       echo json_encode($result);
}
?>

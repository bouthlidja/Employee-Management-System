<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
    $action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 
    if($action == 'searchEmp')
    {
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $employees = array();
        $result = array();
        $sql = "SELECT empID, frstNmEmp, lstNmEmp FROM T_employees WHERE empID = :paramEmpID";
        $stmt = oci_parse($conn, $sql);
    
        oci_bind_by_name($stmt, ':paramEmpID', $id);
    
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($employees, $rows);
                
            }
            $result['status'] = 'success';
            $result['employees'] = $employees;
        } else {
            $e = oci_error($stmt); 
            $result['status'] = 'error';
            $result['message'] = 'Failed to execute query.';
            $result['error'] = $e['message']; 
        }
    
        echo json_encode($result);
    }
    elseif($action == "search"){
        $val = isset($_GET['val']) ? $_GET['val'] : "";
        $retirements  = array();
        $result = array();
        $sql ="SELECT ret.retID,
                    (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,
                    emp.empID,
                    ret.retReas,
                    TO_CHAR( ret.reqDat,'DD-MM-YYYY') AS reqDat,
                    TO_CHAR(  ret.appDat,'DD-MM-YYYY') AS appDat
                FROM T_Retirement ret
                JOIN T_employees emp ON emp.empID = ret.empID
                WHERE ret.retID LIKE :searchValue || '%'
                OR (emp.frstNmEmp || ' ' || emp.lstNmEmp) LIKE :searchValue || '%'";
               
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':searchValue', $val);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($retirements, $rows);
                  
            }
            $result['retirements'] = $retirements;  
        }
        
        echo json_encode($result);
    }
    elseif ($action == 'insert')
    {
        $empID =  test_input(isset($_GET['empID']) )  ?  test_input($_GET['empID']) :   '';
        $reason =  test_input(isset($_GET['reas']) )  ?  test_input($_GET['reas']) :   '';
        $reqDat =  test_input(isset($_GET['reqDat']) )  ?  test_input($_GET['reqDat']) :   '';
        $appDat =  test_input(isset($_GET['appDat']) )  ?  test_input($_GET['appDat']) :   '';

        if (empty($empID) || empty($reason) || empty($reqDat) || empty($appDat) ) {
            $result["status"]= "error";
            $result["message"]= "Missing required fields.";
            echo json_encode($result);
            exit;
        } 

        $checkIdEmp = "SELECT COUNT(*) AS count FROM T_Retirement WHERE empID = :empID";
        $checkIdEmpStmt = oci_parse($conn, $checkIdEmp);
        oci_bind_by_name($checkIdEmpStmt, ':empID', $empID);
        if (oci_execute($checkIdEmpStmt)) {
            $IdEmpRow = oci_fetch_assoc($checkIdEmpStmt);
        }
        if ($IdEmpRow['COUNT']> 0) {
            $result['status'] = 'error';
            $result['message'] = 'The employee is retired.';
            echo json_encode($result);
            exit;
        }
        $sql= "SELECT  REGEXP_SUBSTR(empID, '^\d+') AS serial_number
        FROM t_employees where empID = :paramEmpID";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':paramEmpID', $empID);
        if (oci_execute($stmt)) {
            $row = oci_fetch_assoc($stmt);
            $numEmp = $row['SERIAL_NUMBER']; // استخراج الكود
        }

        $sql = "INSERT INTO  T_Retirement (retID, empID,retReas, reqDat, appDat) 
                VALUES(SEQ_RET.NEXTVAL || '-' || $numEmp, :empID, :retReas, TO_DATE(:reqDat,'YYYY-MM-DD'),  TO_DATE(:appDat,'YYYY-MM-DD'))";

        $stmt = oci_parse($conn, $sql);
    
        oci_bind_by_name($stmt, ':retReas', $reason);
        oci_bind_by_name($stmt, ':reqDat', $reqDat);
        oci_bind_by_name($stmt, ':appDat', $appDat);
        oci_bind_by_name($stmt, ':empID', $empID);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'retirement Insert', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);

            $result['status'] = 'success';
            $result['message'] = 'The retirement has been successfully added.';
        
        } else {
            $e = oci_error($stmt); 
            $result['status'] = 'error';
            $result['message'] = 'The retirement addition failed.';
        }
        echo json_encode($result);       
    }
    elseif($action == 'select')
    {

        $retirements  = array();
        $result = array();
        $sql ="SELECT ret.retID,
                    (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,
                    emp.empID,
                    ret.retReas,
                    TO_CHAR( ret.reqDat,'DD-MM-YYYY') AS reqDat,
                    TO_CHAR(  ret.appDat,'DD-MM-YYYY') AS appDat
                FROM T_Retirement ret
                JOIN T_employees emp ON emp.empID = ret.empID";
               
        $stmt = oci_parse($conn, $sql);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($retirements, $rows);
                  
            }
            $result['retirements'] = $retirements;  
        }
        
        echo json_encode($result);
    }
    elseif($action == 'update'){

        $id =  test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   '';
        $reason =  test_input(isset($_GET['reas']) )  ?  test_input($_GET['reas']) :   '';
        $reqDat =  test_input(isset($_GET['reqDat']) )  ?  test_input($_GET['reqDat']) :   '';
        $appDat =  test_input(isset($_GET['appDat']) )  ?  test_input($_GET['appDat']) :   '';

        if (empty($id) || empty($reason) || empty($reqDat) || empty($appDat) ) {
            $result["status"]= "error";
            $result["message"]= "Missing required fields.";
            echo json_encode($result);
            exit;
        }

        $SQL = "UPDATE T_Retirement
                SET retReas= :retReas,
                    reqDat = TO_DATE(:reqDat, 'YYYY-MM-DD'),
                    appDat = TO_DATE(:appDat, 'YYYY-MM-DD')
                WHERE retID = :retID";
                $stmt = oci_parse($conn, $SQL); 
                oci_bind_by_name($stmt, ':retReas', $reason);
                oci_bind_by_name($stmt, ':reqDat', $reqDat);
                oci_bind_by_name($stmt, ':appDat', $appDat);
                oci_bind_by_name($stmt, ':retID', $id);

        if (oci_execute($stmt)) {
            $rows = oci_num_rows($stmt);
        
            if ($rows > 0) {
                oci_commit($conn);

                $adminID =   $_SESSION['id'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'retirement update', SYSTIMESTAMP, :ipAddress)";
                $logStmt = oci_parse($conn, $logSQL);
                oci_bind_by_name($logStmt, ':adminID', $adminID);
                oci_bind_by_name($logStmt, ':ipAddress', $ip);
                oci_execute($logStmt);
                oci_commit($conn);

                $result["row"] = $rows;
                $result["status"] = "success";
                $result["message"] = "Updated $rows record(s) successfully.";
            } else {
                $result["row"] = $rows;

                $result["status"] = "error";
                $result["message"] = "No records were updated.";
            }
        } else {
            $error = oci_error($stmt);
            $result["status"] = "error";
            $result["message"] = "retirment was not updated successfully. Error: " . $error['message'];
        }
        echo json_encode($result); 

    }
    elseif ($action == 'delete') {
        $retID =  test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   ''; 
        $adminID =   $_SESSION['id'];  
        $ip = $_SERVER['REMOTE_ADDR'];
        //   1. حذف المستخدم أولاً
        $SQL = "DELETE FROM T_Retirement WHERE retID = :retID";
        $stmt = oci_parse($conn, $SQL);
        oci_bind_by_name($stmt, ':retID', $retID);
        if (oci_execute($stmt)) {
            oci_commit($conn);
          //  2. إدراج السجل في `T_log_active` بعد التأكد من نجاح الحذف
          $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                      VALUES (SEQ_LOG.NEXTVAL, :adminID, 'resgnation Delete', SYSTIMESTAMP, :ipAddress )";
          $logStmt = oci_parse($conn, $logSQL);
          oci_bind_by_name($logStmt, ':adminID', $adminID); // المستخدم الذي قام بالحذف
          oci_bind_by_name($logStmt, ':ipAddress', $ip);
          if (oci_execute($logStmt)) {
            oci_commit($conn);
            $result["status"]= "success";
            $result["message"]= "delete  resgnation successfully";
          }   
        } 
        echo json_encode($result); 
    }


?>
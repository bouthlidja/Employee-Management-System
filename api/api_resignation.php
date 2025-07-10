<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
    $action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 

    if($action == 'searchEmp'){

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
    elseif($action == 'search'){
        $val = isset($_GET['val']) ? $_GET['val'] : "";
        $resignations = array();
        $sql = "SELECT 
        re.resgID, 
        TO_CHAR(re.empConfDat,'DD-MM-YYYY') AS empConfDat,
        re.resgRreas,
       
        TO_CHAR(re.resgEffDat,'DD-MM-YYYY') AS resgEffDat,
        (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,  
        emp.empID   
        FROM 
        T_resignations re
        JOIN 
        T_employees emp ON emp.empID = re.empID
                WHERE re.resgID LIKE :searchValue || '%'
                OR (emp.frstNmEmp || ' ' || emp.lstNmEmp) LIKE :searchValue || '%' ";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':searchValue', $val);

        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($resignations, $rows);  
            }
            $result['resignations'] = $resignations;  
        }
         
        echo json_encode($result);
    }
    elseif($action == 'select'){
        $resignations = array();
        $sql =" SELECT 
        re.resgID, 
        TO_CHAR(re.empConfDat,'DD-MM-YYYY') AS empConfDat,
        re.resgRreas,
       
        TO_CHAR(re.resgEffDat,'DD-MM-YYYY') AS resgEffDat,
        (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,  
        emp.empID   
        FROM 
        T_resignations re
        JOIN 
        T_employees emp ON emp.empID = re.empID";
        $stmt = oci_parse($conn, $sql);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($resignations, $rows);  
            }
            $result['resignations'] = $resignations;  
        }
        
        echo json_encode($result);
    }
    elseif($action == 'insert'){
        $empID =  test_input(isset($_GET['empID']) )  ?  test_input($_GET['empID']) :   '';
        $reason =  test_input(isset($_GET['reas']) )  ?  test_input($_GET['reas']) :   '';
        $reqDat =  test_input(isset($_GET['reqDat']) )  ?  test_input($_GET['reqDat']) :   '';
        $resDat =  test_input(isset($_GET['resDat']) )  ?  test_input($_GET['resDat']) :   '';
        
        if (empty($empID) || empty($reason) || empty($reqDat) || empty($resDat)) {
            $result['status'] = 'error';
            $result['message'] = 'Missing required fields';
            echo json_encode($result);
            exit;
        }
        $checkEmp= "SELECT COUNT(empID) AS count FROM T_resignations WHERE empID = :empID";
        $checkEmpStmt = oci_parse($conn, $checkEmp);
        oci_bind_by_name($checkEmpStmt, ':empID', $empID);
        oci_execute($checkEmpStmt);
        $empRow = oci_fetch_assoc($checkEmpStmt);

        if($empRow['COUNT']> 0){
            $result["status"]= "error";
            $result["message"]= "The employee is already resigned.";
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
           
        $sql = "INSERT INTO  T_resignations (resgID, empConfDat,   resgRreas, resgEffDat,  empID) 
                VALUES(SEQ_RESG.NEXTVAL || '-' ||$numEmp, TO_DATE(:empConfDat,'YYYY-MM-DD'), :resgRreas,  TO_DATE(:resgEffDat,'YYYY-MM-DD'), :empID)";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':empConfDat', $reqDat);
        oci_bind_by_name($stmt, ':resgRreas', $reason);
        oci_bind_by_name($stmt, ':resgEffDat', $resDat);
        oci_bind_by_name($stmt, ':empID', $empID);

        if (oci_execute($stmt)) {
            oci_commit($conn);

            $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'resignation Insert', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);

            $result['status'] = 'success';
            $result['message'] = 'The resignation has been successfully added';
           
        } else {
            $e = oci_error($stmt); 
            $result['status'] = 'error';
            $result['message'] = 'Failed to add the resignation';
        }
        
        echo json_encode($result);


    }
    elseif ($action == 'update') {
        $rsgID =  test_input(isset($_GET['rsgID']))  ?  test_input($_GET['rsgID']) :   '';
        $reason =  test_input(isset($_GET['reas']))  ?  test_input($_GET['reas']) :   '';
        $reqDat =  test_input(isset($_GET['reqDat']))  ?  test_input($_GET['reqDat']) :   '';
        $resDat =  test_input(isset($_GET['resDat']))  ?  test_input($_GET['resDat']) :   '';

        if (empty($rsgID) || empty($reason) || empty($reqDat) || empty($resDat)) {
            $result['status'] = 'error';
            $result['message'] = 'Missing required fields';
            echo json_encode($result);
            exit;
        }
        $SQL = "UPDATE T_resignations 
        SET   resgRreas = : resgRreas,
            
           empConfDat = TO_DATE(:empConfDat, 'YYYY-MM-DD'),
            resgEffDat = TO_DATE(:paramStrtDat, 'YYYY-MM-DD')
        WHERE resgID = :resgID";
        $stmt = oci_parse($conn, $SQL); 
        oci_bind_by_name($stmt, ':resgRreas', $reason);
        oci_bind_by_name($stmt, ':empConfDat', $reqDat);
        oci_bind_by_name($stmt, ':paramStrtDat', $resDat);
        oci_bind_by_name($stmt, ':resgID', $rsgID);
        if (oci_execute($stmt)) {
            $rows = oci_num_rows($stmt);
        
            if ($rows > 0) {
                oci_commit($conn);


                $adminID =   $_SESSION['id'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'resignation update', SYSTIMESTAMP, :ipAddress)";
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
            $result["message"] = "resgination was not updated successfully. Error: " . $error['message'];
        }
        echo json_encode($result);
    }
    elseif($action == 'delete'){ 
        $rsgID =  test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   ''; 
        $adminID =   $_SESSION['id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $SQL = "DELETE FROM T_resignations WHERE resgID = :resgID";
        $stmt = oci_parse($conn, $SQL);
        oci_bind_by_name($stmt, ':resgID', $rsgID);
      
        if (oci_execute($stmt)) {
            oci_commit($conn);
          //  2. إدراج السجل في `T_log_active` بعد التأكد من نجاح الحذف
          $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                      VALUES (SEQ_LOG.NEXTVAL, :adminID, 'resgnation Delete', SYSTIMESTAMP, :ipAddress )";
          
          $logStmt = oci_parse($conn, $logSQL);
          oci_bind_by_name($logStmt, ':adminID', $adminID); 
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
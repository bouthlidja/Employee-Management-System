<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';
include('../includes/functions/functions.php');
    $action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 
    
    if($action == 'searchEmp'){
        $id =  isset($_GET['id']) ? $id = $_GET['id'] : $id = "" ;
        $employees = array();
        $sql = 'SELECT T_employees.empID , T_employees.frstNmEmp || \' \' || T_employees.lstNmEmp  as "FULLNAME", T_Departments.depName
        FROM T_employees 
        JOIN T_Departments ON T_employees.DepID = T_Departments.DepID 
        WHERE T_employees.empID = :paramEmpID';
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
        echo json_encode($result);
    }
    elseif($action == "searchTransfer")
    {
        $val = test_input(isset($_GET['val'])) ? test_input($_GET['val']) : "";
        $transfers = array();
       
        $sql = " SELECT 
        tr.traID, 
        tr.currWrkp,
        dep.depName,
         dep.depID,
        TO_CHAR(tr.orgDepAppDat, 'DD-MM-YYYY') AS datOrgDept,
        TO_CHAR(tr.recDepAppDat, 'DD-MM-YYYY') AS recDepAppDat,   
        TO_CHAR(tr.newWrkStrDat, 'DD-MM-YYYY') AS newWrkStrDat,
        (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,  
        tr.empID
        FROM 
            t_transfers tr
        JOIN 
            T_employees emp ON emp.empID = tr.empID
        JOIN 
        T_departments dep ON dep.depID = tr.depID
         WHERE tr.traID LIKE :searchValue || '%'
        OR (emp.frstNmEmp || ' ' || emp.lstNmEmp) LIKE :searchValue || '%'";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':searchValue', $val);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($transfers, $rows);  
            }
            
            $result['transfers'] = $transfers;  
        }
        
        echo json_encode($result);
        
    }
    elseif($action ==='select'){
        $transfers = array();
        $sql = " SELECT 
        tr.traID, 
        tr.currWrkp,
        dep.depName,
         dep.depID,
        TO_CHAR(tr.orgDepAppDat, 'DD-MM-YYYY') AS datOrgDept,
        TO_CHAR(tr.recDepAppDat, 'DD-MM-YYYY') AS recDepAppDat,   
        TO_CHAR(tr.newWrkStrDat, 'DD-MM-YYYY') AS newWrkStrDat,
        (emp.frstNmEmp || ' ' || emp.lstNmEmp) AS fullName,  
        tr.empID
        FROM 
            t_transfers tr
        JOIN 
            T_employees emp ON emp.empID = tr.empID
        JOIN 
        T_departments dep ON dep.depID = tr.depID";
            $stmt = oci_parse($conn, $sql);
            if (oci_execute($stmt)) {
                while ($rows = oci_fetch_assoc($stmt)) {
                    array_push($transfers, $rows);  
                }
                
                $result['transfers'] = $transfers;  
            }
            
            echo json_encode($result);
    }

    elseif($action == 'insert'){
        $OrgDept =  test_input(isset($_GET['OrgDept']) )  ?  test_input($_GET['OrgDept']) :   ''; 
        $DatOrgApp = test_input(isset($_GET['DatOrgApp']) )  ?  test_input($_GET['DatOrgApp']) :   '';
        $NewDept =  test_input(isset($_GET['NewDept']) )  ?  test_input($_GET['NewDept']) :   '' ;
        $DatNewApp = test_input(isset($_GET['DatNewApp']) )  ?  test_input($_GET['DatNewApp']) :   ''  ;
        $StrtDat = test_input(isset($_GET['StrtDat']) )  ?  test_input($_GET['StrtDat']) :   '' ;
        $EmpID = test_input(isset($_GET['EmpID']) )  ?  test_input($_GET['EmpID']) :   '' ;

        if (empty($OrgDept) || empty($DatOrgApp) || empty($NewDept) || empty($DatNewApp) || empty($StrtDat) || empty($EmpID) ) {
            $result["status"]= "error";
            $result["message"]= "Missing required fields.";
            echo json_encode($result);
            exit;
        }

        $sql = "INSERT INTO T_Transfers(traID, currWrkp , orgDepAppDat, depID, recdepappdat, newwrkstrdat, empid)
        VALUES (
        SEQ_TRN.nextval || '-' || EXTRACT(YEAR FROM SYSDATE), 
        :paramOrgDept, 
        TO_DATE(:paramDatOrgApp, 'YYYY-MM-DD'), 
        :paramNewDept,
        TO_DATE(:paramDatNewApp, 'YYYY-MM-DD'), 
        TO_DATE(:paramStrtDat, 'YYYY-MM-DD'), 
        :paramEmpID)";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':paramOrgDept', $OrgDept);
        oci_bind_by_name($stmt, ':paramDatOrgApp', $DatOrgApp);
        oci_bind_by_name($stmt, ':paramNewDept', $NewDept);
        oci_bind_by_name($stmt, ':paramDatNewApp', $DatNewApp);
        oci_bind_by_name($stmt, ':paramStrtDat', $StrtDat);
        oci_bind_by_name($stmt, ':paramEmpID', $EmpID);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'transfer Insert', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);

            $result['status'] = 'success';
            $result['message'] = "The employee number $EmpID has been transferred to the department  $NewDept.";
 
        } else {
            $e = oci_error($stmt); 
        $result['status'] = 'error';
        $result['message'] = 'The transfer process failed.';
        }

        

        $sqlSelectEmpID = "SELECT DepID FROM T_employees WHERE empID = :paramSelEmpID";
        $stmtSelectEmpID = oci_parse($conn, $sqlSelectEmpID);
        oci_bind_by_name($stmtSelectEmpID, ':paramSelEmpID', $EmpID);
        if (oci_execute($stmtSelectEmpID)) {
            $row = oci_fetch_assoc($stmtSelectEmpID);
            if ($row) {
                
                $DepID = $row['DEPID'];  
               
            } 
           
        } 
        $EmpIDUPdate = test_input(isset($_GET['EmpID']) )  ?  test_input($_GET['EmpID']) :   '' ;
        
        $sqlUpdate = "UPDATE T_employees SET  DepID = :paramNewDepID  WHERE empID = :paramEmpID";
        
        $stmt = oci_parse($conn, $sqlUpdate); 

        oci_bind_by_name($stmt, ':paramEmpID', $EmpIDUPdate);
        oci_bind_by_name($stmt, ':paramNewDepID', $NewDept);
        if (oci_execute($stmt)) {
            oci_commit($conn);
            
        } 
        echo json_encode($result);
    }
    
    elseif ($action =='update') {
        
        $DatOrgApp = test_input(isset($_GET['DatOrgApp']) )  ?  test_input($_GET['DatOrgApp']) :   '';
        $NewDept =  test_input(isset($_GET['NewDept']) )  ?  test_input($_GET['NewDept']) :   '' ;
        $DatNewApp = test_input(isset($_GET['RecDepAppDat']) )  ?  test_input($_GET['RecDepAppDat']) :   ''  ;
        $StrtDat = test_input(isset($_GET['StrtDat']) )  ?  test_input($_GET['StrtDat']) :   '' ;
        $id = test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   '' ;

        $SQL = "UPDATE T_Transfers 
        SET orgDepAppDat = TO_DATE(:paramDatOrgApp, 'YYYY-MM-DD'),
            DepID = :paramNewDept, 
            recDepAppDat = TO_DATE(:paramRecDepAppDat, 'YYYY-MM-DD'),
            newWrkStrDat = TO_DATE(:paramStrtDat, 'YYYY-MM-DD')
        WHERE traID = :paramID";
        
        $stmt = oci_parse($conn, $SQL); 
        oci_bind_by_name($stmt, ':paramDatOrgApp', $DatOrgApp);
        oci_bind_by_name($stmt, ':paramNewDept', $NewDept);
        oci_bind_by_name($stmt, ':paramRecDepAppDat', $DatNewApp);
        oci_bind_by_name($stmt, ':paramStrtDat', $StrtDat);
        oci_bind_by_name($stmt, ':paramID', $id);

        if (oci_execute($stmt)) {
            $rows = oci_num_rows($stmt);
        
            if ($rows > 0) {
                oci_commit($conn);
                $adminID =   $_SESSION['id'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                VALUES (SEQ_LOG.NEXTVAL, :adminID, 'transfer update', SYSTIMESTAMP, :ipAddress)";
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
            $result["message"] = "Transfers was not updated successfully. Error: " . $error['message'];
        }
        echo json_encode($result); 
    }
    elseif($action == 'delete'){ 
        $trnID =  test_input(isset($_GET['id']) )  ?  test_input($_GET['id']) :   ''; 
        $adminID =   $_SESSION['id']; 
        $ip = $_SERVER['REMOTE_ADDR'];
       
        //   1. حذف سجل النقل أولاً
        $SQL = "DELETE FROM t_transfers WHERE traID = :traID";
        $stmt = oci_parse($conn, $SQL);
        oci_bind_by_name($stmt, ':traID', $trnID);
      
        if (oci_execute($stmt)) {
            oci_commit($conn);
          //  2. إدراج السجل في `T_log_active` بعد التأكد من نجاح الحذف
          $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                      VALUES (SEQ_LOG.NEXTVAL, :adminID, 'transfer Delete', SYSTIMESTAMP, :ipAddress )";
          
          $logStmt = oci_parse($conn, $logSQL);
          oci_bind_by_name($logStmt, ':adminID', $adminID); // المستخدم الذي قام بالحذف
          oci_bind_by_name($logStmt, ':ipAddress', $ip);
      
          if (oci_execute($logStmt)) {
            oci_commit($conn);
            $result["status"]= "success";
            $result["message"]= "delete  transfer successfully";
          }   
        } 
        echo json_encode($result); 
    }
    elseif($action == "reportTransfer"){
        $strtDat = test_input(isset($_GET['strtDat'])) ? test_input($_GET['strtDat']) : "";
        $endDat = test_input(isset($_GET['endDat'])) ? test_input($_GET['endDat']) : "";
 
        $transfers = array();
        $sql = "SELECT 
                t.traID  ,
                e.empID ,
                (e.frstNmEmp ||' ' ||  e.lstNmEmp) AS full_name,
                d_old.depName,
                d_new.depName ,
                TO_DATE(t.orgDepAppDat, 'DD-MM-YYYY') AS orgDepAppDat,
                TO_DATE(t.recDepAppDat, 'DD-MM-YYYY') AS recDepAppDat,
                TO_DATE(t.newWrkStrDat, 'DD-MM-YYYY') AS newWrkStrDat
                FROM 
                    T_transfers t
                JOIN 
                    T_employees e ON t.empID = e.empID
                LEFT JOIN 
                    T_departments d_old ON t.currWrkp = d_old.DepID
                LEFT JOIN 
                    T_departments d_new ON t.DepID = d_new.DepID
                WHERE 
                    t.newWrkStrDat BETWEEN TO_DATE(:strtDat, 'YYYY-MM-DD') 
                                    AND TO_DATE(:endDat, 'YYYY-MM-DD') 
                ORDER BY 
                t.newWrkStrDat DESC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':strtDat', $strtDat);
        oci_bind_by_name($stmt, ':endDat', $endDat);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($transfers, $rows);  
            }
            $result['status'] = 'success';
            $result['transfers'] = $transfers;  
        }
        echo json_encode($result);
    }
                           
 ?> 
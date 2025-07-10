<?php
session_start();
header('Content-Type: application/json');

 include '../connect.php';
 include "../includes/functions/functions.php";
 $action = isset($_GET['action']) ?  $_GET['action'] :   ''; 

if ($action == "searchEmp")
{
    $id = isset($_GET['empID']) ? $_GET['empID'] : "";
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
elseif($action == "searchLev")
{
    $val = isset($_GET['val']) ? $_GET['val'] : "";
    $leaves = array();
    $sql = "SELECT 
            l.lvsID, 
            l.lvstyp, 
            l.lvsReas, l.lvsdur,
            TO_CHAR( l.lvsstrtdat, 'DD-MM-YYYY') AS lvsstrtdat, 
            TO_CHAR( l.lvsenddat, 'DD-MM-YYYY') AS lvsenddat ,
            l.lvsstts,
            e.frstNmEmp || ' ' || e.lstNmEmp AS full_name
            FROM T_Leaves l
            JOIN T_employees e ON l.empID = e.empID
            WHERE l.lvsID LIKE :searchValue || '%'
            OR (e.frstNmEmp || ' ' || e.lstNmEmp) LIKE :searchValue || '%' ";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':searchValue', $val);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($leaves, $rows);
        }

        $result['status'] = 'success';
        $result['leaves'] = $leaves;
    } else {
        $e = oci_error($stmt);
        $result['status'] = 'error';
        $result['message'] = 'Failed to execute query.';
        $result['error'] = $e['message']; 
    }
    echo json_encode($result);
}
elseif ($action == "select")
{
   
    $currentDate = '';
    $currentSql = "SELECT TO_DATE(TO_CHAR(SYSDATE, 'DD-MM-YYYY'), 'DD-MM-YYYY') AS formatted_date FROM DUAL";
    $currentStmt = oci_parse($conn, $currentSql);
    oci_execute($currentStmt);
    $currentRow = oci_fetch_assoc($currentStmt);
    $currentDate = $currentRow['FORMATTED_DATE'];

    // تحديث حالة العطلات بناءً على المقارنة مع التاريخ الحالي
    $updateSql = " UPDATE T_Leaves
                    SET lvsStts = CASE 
                        WHEN lvsEndDat < SYSDATE THEN 'Ended' 
                        ELSE 'not Ended' 
                     END";
    $updateStmt = oci_parse($conn, $updateSql);
    oci_execute($updateStmt); 
    oci_commit($conn);
 
    // جلب جميع العطلات بعد التحديث
    $leaves = array();
    $sql = "SELECT l.lvsID, l.empID, 
            e.frstNmEmp || ' ' || e.lstNmEmp AS full_name, 
            l.lvstyp, l.lvsReas, l.lvsDur, 
            TO_CHAR(l.lvsStrtDat, 'DD-MM-YYYY') AS start_date, 
            TO_CHAR(l.lvsEndDat, 'DD-MM-YYYY') AS end_date, 
            l.lvsStts
            FROM t_leaves l
            JOIN t_employees e ON l.empID = e.empID
            ORDER BY l.lvsID DESC";
    $stmt = oci_parse($conn, $sql);
    // تنفيذ الاستعلام
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($leaves, $rows);  
        }
        $result['leaves'] = $leaves;  
    echo json_encode($result);
}

elseif ($action == "insert")
{
    $empID  = isset($_GET['empID']) ? $_GET['empID'] : "";
    $lvstyp  = isset($_GET['lvstyp']) ? $_GET['lvstyp'] : "";
    $lvsReas = isset($_GET['lvsReas']) ? $_GET['lvsReas'] : "";
    $lvsDur = isset($_GET['lvsDur']) ? $_GET['lvsDur'] : "";
    $lvsStrtDat = isset($_GET['lvsStrtDat']) ? $_GET['lvsStrtDat'] : "";

    if (empty($empID)  || empty($lvstyp) || empty($lvsReas) || empty($lvsDur) || empty($lvsStrtDat)) {
        $result['status'] = 'error';
        $result['message'] = 'Missing required fields';
        echo json_encode($result);
        exit;
    }

    // التحقق مما إذا كان الموظف قد استفاد من إجازة الحج مسبقًا
    $sql = "SELECT COUNT(*) AS total FROM T_Leaves WHERE lvstyp = 'Hajj leave' AND empID = :empID";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':empID', $empID);
    oci_execute($stmt);
    $RowHajj = oci_fetch_assoc($stmt);

    // التحقق مما إذا كان الموظف قد استفاد من الإجازة السنوية هذه السنة فقط
    $sql = "SELECT COUNT(*) AS annual_leave_count FROM T_Leaves WHERE lvstyp = 'Annual leave' AND empID = :empID AND EXTRACT(YEAR FROM lvsStrtDat) = EXTRACT(YEAR FROM SYSDATE)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':empID', $empID);
    oci_execute($stmt);
    $RowAnn = oci_fetch_assoc($stmt);

    if ($lvstyp == 'Hajj leave' && $RowHajj['TOTAL'] > 0) {
        $result["status"] = "error";
        $result["message"] = "The employee has already used their Hajj leave and cannot take another.";
    } elseif ($lvstyp == 'Annual leave' && $RowAnn['ANNUAL_LEAVE_COUNT'] > 0) {
        $result["status"] = "error";
        $result["message"] = "The employee has already used their Annual leave for this year.";
    } else {
        try {
            $startDate = new DateTime($lvsStrtDat);
            $startDate->modify("+{$lvsDur} days");
            $lvsEndDat = $startDate->format('Y-m-d');

            $sql = "INSERT INTO T_Leaves (
                        lvsID,   lvstyp, lvsReas, lvsDur, lvsStrtDat, lvsEndDat, empID
                    ) VALUES (
                        SEQ_LVS.nextval || '-' || EXTRACT(YEAR FROM SYSDATE),
                          :paramTypeLeave, :paramReasonLeave, 
                        :paramDurationLeave, TO_DATE(:paramStartDate, 'YYYY-MM-DD'), 
                        TO_DATE(:paramEndDate, 'YYYY-MM-DD'), :paramEmpID
                    )";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':paramTypeLeave', $lvstyp);
            oci_bind_by_name($stmt, ':paramReasonLeave', $lvsReas);
            oci_bind_by_name($stmt, ':paramDurationLeave', $lvsDur);
            oci_bind_by_name($stmt, ':paramStartDate', $lvsStrtDat);
            oci_bind_by_name($stmt, ':paramEndDate', $lvsEndDat);
            oci_bind_by_name($stmt, ':paramEmpID', $empID);

            if (oci_execute($stmt)) {
                oci_commit($conn);

                $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'leave Insert', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);

                $result['status'] = 'success';
                $result['message'] = 'Leave inserted successfully.';
            } else {
                $e = oci_error($stmt);
                $result['status'] = 'error';
                $result['message'] = 'Database error';
                $result['error'] = $e['message'];
            }
        } catch (Exception $e) {
            $result['status'] = 'error';
            $result['message'] = 'Invalid date format';
        }
    }

    echo json_encode($result);
}

elseif ($action == 'update')
{
    $id = isset($_GET['lvsID']) ? $_GET['lvsID'] : "";
    $typ = isset($_GET['lvstyp']) ? $_GET['lvstyp'] : "";
    $rsn = isset($_GET['lvsReas']) ? $_GET['lvsReas'] : "";
    $dur = isset($_GET['lvsDur']) ? $_GET['lvsDur'] : "";
    $strDat = isset($_GET['lvsStrtDat']) ? $_GET['lvsStrtDat'] : "";

    if (empty($id) || empty($typ) || empty($rsn) || empty($dur) || empty($strDat)) {
        $result['status'] = 'error';
        $result['message'] = 'Missing required fields';
        echo json_encode($result);
        exit;
    }

    // SQL مع تحديث تاريخ الانتهاء
    $SQL = "UPDATE t_leaves 
            SET lvstyp = :paramLvstyp, 
                lvsreas = :paramLvsreas, 
                lvsdur = :paramLvsdur, 
                lvsstrtdat = TO_DATE(:paramstrtdat, 'YYYY-MM-DD'),
                lvsEndDat = TO_DATE(:paramstrtdat, 'YYYY-MM-DD') + :paramLvsdur 
            WHERE lvsid = :paramLvsID";

    $stmt = oci_parse($conn, $SQL);

    oci_bind_by_name($stmt, ':paramLvstyp', $typ);
    oci_bind_by_name($stmt, ':paramLvsreas', $rsn);
    oci_bind_by_name($stmt, ':paramLvsdur', $dur);
    oci_bind_by_name($stmt, ':paramstrtdat', $strDat);
    oci_bind_by_name($stmt, ':paramLvsID', $id);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $adminID =   $_SESSION['id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
        VALUES (SEQ_LOG.NEXTVAL, :adminID, 'leave update', SYSTIMESTAMP, :ipAddress)";
        $logStmt = oci_parse($conn, $logSQL);
        oci_bind_by_name($logStmt, ':adminID', $adminID);
        oci_bind_by_name($logStmt, ':ipAddress', $ip);
        oci_execute($logStmt);
        oci_commit($conn);
        $result["status"] = "success";
        $result["message"] = "Update leave successfully, end date updated.";
        echo json_encode($result);
    } else {
        $error = oci_error($stmt); 
        $result["status"] = "error";
        $result["message"] = "Failed to update leave: " . $error['message'];
        echo json_encode($result);
    }
}
elseif($action == 'delete')
{
   
    $adminID =   $_SESSION['id']; 
    $lvsid  = isset($_GET['id']) ? $_GET['id'] : "";
    $ip = $_SERVER['REMOTE_ADDR'];

    $SQL = "DELETE FROM T_leaves WHERE lvsid = :lvsid";
    $stmt = oci_parse($conn, $SQL);
    oci_bind_by_name($stmt, ':lvsid', $lvsid);
  
    if (oci_execute($stmt)) {
        oci_commit($conn);
      $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                  VALUES (SEQ_LOG.NEXTVAL, :adminID, 'leave Delete', SYSTIMESTAMP, :ipAddress )";
      
      $logStmt = oci_parse($conn, $logSQL);
      oci_bind_by_name($logStmt, ':adminID', $adminID); // المستخدم الذي قام بالحذف
      oci_bind_by_name($logStmt, ':ipAddress', $ip);
  
      if (oci_execute($logStmt)) {
        oci_commit($conn);
        $result["status"]= "success";
        $result["message"]= "delete  employee successfully";
      }   
    } 
    echo json_encode($result); 
}

?>
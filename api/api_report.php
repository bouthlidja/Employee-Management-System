<?php
header('Content-Type: application/json');
    include '../connect.php';
    include('../includes/functions/functions.php');
    $action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 
    if($action == 'reportAnnual'){
        $year = test_input(isset($_GET['year']) )  ?  test_input($_GET['year']) :   '';
    
        if (empty($year)) {
            $result["status"]= "error";
            $result["message"]= "Missing required fields.";
            echo json_encode($result);
            exit;
        }
        $empLeave = array();
        $empNoLeave = array();
        $sql = "SELECT DISTINCT l.lvsID, e.empID, (e.frstNmEmp|| ' ' || e.lstNmEmp) AS fullName
        FROM T_employees e
        JOIN T_Leaves l ON e.empID = l.empID
        WHERE l.lvstyp = 'Annual leave' 
        AND EXTRACT(YEAR FROM l.lvsStrtDat) = :yearLvs";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':yearLvs', $year);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($empLeave, $rows);  
            }
            $result['empLeave'] = $empLeave;  
        }

        $sql = "SELECT DISTINCT l.lvsID, e.empID, (e.frstNmEmp|| ' ' || e.lstNmEmp) AS fullName
                FROM T_employees e
                LEFT JOIN T_Leaves l 
                    ON e.empID = l.empID 
                    AND l.lvstyp = 'Annual leave'
                    AND EXTRACT(YEAR FROM l.lvsStrtDat) = :yearNotLvs WHERE l.lvsID IS NULL";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':yearNotLvs', $year);

        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($empNoLeave, $rows);  
            }
            $result['empNoLeave'] = $empNoLeave;  
        }



        echo json_encode($result);
    }
    elseif($action == 'LvRptEmp'){
        $empID = test_input(isset($_GET['empID']) )  ?  test_input($_GET['empID']) :   '';
        $startDate = test_input(isset($_GET['startDate']) )  ?  test_input($_GET['startDate']) :   '';
        $endDate = test_input(isset($_GET['endDate']) )  ?  test_input($_GET['endDate']) :   '';
        if (empty($empID)) {
            $result["status"]= "error";
            $result["message"]= "Missing required fields.";
            echo json_encode($result);
            exit;
        }
        $infoEmp = array();
        $infoLvs = array();
        $sql = "SELECT empID, (frstNmEmp || ' ' || lstNmEmp) AS fullName
                FROM t_employees 
                WHERE empID = :empID";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':empID', $empID);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($infoEmp, $rows);  
            }
            $result['status'] = 'success';
            $result['infoEmp'] = $infoEmp;  
        }
        $sql = "SELECT 
                l.lvstyp AS leave_type,
                COUNT(*) AS total_leaves
                FROM T_Leaves l
                JOIN T_employees e ON e.empID = l.empID
                WHERE e.empID = :empID
                AND l.lvsStrtDat >= TO_DATE(:startDate, 'YYYY-MM-DD')
                AND l.lvsStrtDat <= TO_DATE(:endDate, 'YYYY-MM-DD')
                GROUP BY l.lvstyp
                ORDER BY total_leaves DESC";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':empID', $empID);
        oci_bind_by_name($stmt, ':startDate', $startDate);
        oci_bind_by_name($stmt, ':endDate', $endDate);
        if (oci_execute($stmt)) {
            while ($rows = oci_fetch_assoc($stmt)) {
                array_push($infoLvs, $rows);  
            }
            $result['status'] = 'success';
            $result['infoLvs'] = $infoLvs;  
        }


        echo json_encode($result);

    }




?>
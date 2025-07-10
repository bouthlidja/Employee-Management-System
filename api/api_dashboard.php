<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

include "../includes/functions/functions.php";

$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 
if ($action =='select') {
    $log = array();
    $sql = "SELECT logID, usrID, action_type, 
            TO_CHAR(action_time, 'DD-MM-YYYY HH24:MI:SS') AS action_time, ip_address
            FROM T_log_active
            ORDER BY logID DESC
            FETCH FIRST 5 ROWS ONLY";
    $stmt = oci_parse($conn, $sql);

    
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($log, $rows);  
        }
        $result['logs'] = $log;  
    }
    echo json_encode($result);    
}

elseif ($action == 'totalLogs' ) {
    echo getCount($conn,'T_log_active');

}
elseif ($action == 'todayLogs')
{
    $sql = "SELECT COUNT(logID) AS count_today
            FROM T_log_active
            WHERE action_time >= TRUNC(SYSDATE)";

     $stmt = oci_parse($conn, $sql);
     oci_execute($stmt) ;
                 
     $row = oci_fetch_assoc($stmt);
     $result['status'] = 'success';
     $result['count'] = $row['COUNT_TODAY'];    
      echo   json_encode($result);
  
}
elseif ($action == 'mostFrequentAction') {
    $frequents = array();
    $sql = "SELECT action_type, COUNT(*) AS action_count
            FROM T_log_active
            GROUP BY action_type
            ORDER BY action_count DESC
            FETCH FIRST 1 ROWS ONLY";
     $stmt = oci_parse($conn, $sql);
   
                 


     if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($frequents, $rows);
        }
     }
   
     $result['status'] = 'success';
     $result['frequents'] = $frequents;    
      echo   json_encode($result);

}



elseif ($action == 'deleteSelected') {
    // استقبال البيانات المرسلة كـ JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['logIDs']) && is_array($data['logIDs']) && !empty($data['logIDs'])) {
        $ids = array_map('intval', $data['logIDs']); // تأكيد أن القيم أرقام صحيحة
        
        // تحويل المصفوفة إلى قائمة مفصولة بفواصل
        $idList = implode(',', $ids);

        // إنشاء الاستعلام مباشرة بدون bind
        $sql = "DELETE FROM T_log_active WHERE logID IN ($idList)";
        $stmt = oci_parse($conn, $sql);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $result["status"]= "success";
            $result["message"]= "Selected records have been successfully deleted.";
        } else {
            $result["status"]= "error";
            $result["message"]= "Selected records have been successfully deleted.";
        }
    } else {
        $result["status"]= "error";
        $result["message"]= "No records selected.";
    }
    echo json_encode($result); 

}

elseif($action == 'showAll'){
    $log = array();
    $sql = "SELECT logID, usrID, action_type, 
            TO_CHAR(action_time, 'DD-MM-YYYY HH24:MI:SS') AS action_time, ip_address
            FROM T_log_active
            ORDER BY logID DESC";
    $stmt = oci_parse($conn, $sql);

    
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($log, $rows);  
        }
        $result['logs'] = $log;  
    }
    echo json_encode($result);
}
elseif($action == 'search'){
    $val = isset($_GET['val']) ? $_GET['val'] : "";
    $log = array();
    $sql = "SELECT logID, usrID, action_type, 
            TO_CHAR(action_time, 'DD-MM-YYYY HH24:MI:SS') AS action_time, ip_address
            FROM T_log_active
            WHERE logID LIKE :searchValue || '%'
            OR usrID LIKE :searchValue || '%'";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':searchValue', $val);

    
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($log, $rows);  
        }
        $result['logs'] = $log;  
    }
    echo json_encode($result);
}
elseif ($action == 'logsPerDay') {

    // 1. اجلب التاريخ الحالي بدون وقت (current_date = TRUNC(SYSDATE))
    // 2. تحدد تاريخ بداية الأسبوع الأخير (start_date = current_date - 6 أيام)
    // 3. استرجع العمليات من جدول T_log_active حيث:
    //    - action_time >= start_date (أي خلال آخر 7 أيام)
    // 4. لكل عملية في النطاق، قم بـ:
    //    - تحويل action_time إلى اسم اليوم (بالإنجليزية)
    //    - حساب عدد العمليات لكل يوم
    // 5. رتب النتائج بناءً على أول عملية تمت في كل يوم
    // 6. أظهر النتيجة

    $sql = "SELECT 
                TO_CHAR(action_time, 'Day', 'NLS_DATE_LANGUAGE=ENGLISH') AS action_day,  
                COUNT(action_type) AS action_count
            FROM T_log_active
            WHERE action_time >= TRUNC(SYSDATE) - INTERVAL '6' DAY
            GROUP BY TO_CHAR(action_time, 'Day', 'NLS_DATE_LANGUAGE=ENGLISH')
            ORDER BY MIN(action_time)";

    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    $data = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $data[] = [
            "day" => trim($row["ACTION_DAY"]), // إزالة الفراغات
            "count" => (int)$row["ACTION_COUNT"]
        ];
    }

    echo json_encode(["status" => "success", "logs" => $data]);
}
elseif ($action == 'operationDistribution') {
    $sql = "SELECT 
                action_type, 
                COUNT(*) AS action_count, 
                ROUND((COUNT(*) * 100) / (SELECT COUNT(*) FROM T_log_active), 2) AS percentage
            FROM T_log_active
            GROUP BY action_type
            ORDER BY action_count DESC";

    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    $operations = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $operations[] = $row;
    }

    echo json_encode(["status" => "success", "operations" => $operations]);
}

?>
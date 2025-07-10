<?php

header('Content-Type: application/json');

 include '../connect.php';
 include "../includes/functions/functions.php";
 $action = isset($_GET['action']) ?  $_GET['action'] :   ''; 

if ($action == "searchEmp") {
    $id = isset($_GET['empID']) ? $_GET['empID'] : "";
    $employees = array();
    $result = array(); // التأكد من أن المتغير $result مُعرّف دائمًا

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
        $e = oci_error($stmt); // جلب معلومات الخطأ
        $result['status'] = 'error';
        $result['message'] = 'Failed to execute query.';
        $result['error'] = $e['message']; // إرسال رسالة الخطأ للمتصفح
    }

    // إرجاع استجابة JSON صالحة دائمًا
    echo json_encode($result);
}
elseif($action == "searchLev"){
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
        $e = oci_error($stmt); // جلب معلومات الخطأ
        $result['status'] = 'error';
        $result['message'] = 'Failed to execute query.';
        $result['error'] = $e['message']; // إرسال رسالة الخطأ للمتصفح
    }
    echo json_encode($result);
}
elseif ($action == "select") {
    // جلب التاريخ الحالي بتنسيق DD-MM-YYYY
    $currentDate = '';
    $currentSql = "SELECT TO_DATE(TO_CHAR(SYSDATE, 'DD-MM-YYYY'), 'DD-MM-YYYY') AS formatted_date FROM DUAL";
    $currentStmt = oci_parse($conn, $currentSql);
    oci_execute($currentStmt);
    $currentRow = oci_fetch_assoc($currentStmt);
    $currentDate = $currentRow['FORMATTED_DATE'];

    // تحديث حالة العطلات بناءً على المقارنة مع التاريخ الحالي
    $updateSql = "
        UPDATE T_Leaves
        SET lvsStts = CASE 
                        WHEN lvsEndDat < SYSDATE THEN 'Ended' 
                        ELSE 'not Ended' 
                     END
    ";
    $updateStmt = oci_parse($conn, $updateSql);
    oci_execute($updateStmt); // تنفيذ التحديث
 // echo $currentDate ;
    // جلب جميع العطلات بعد التحديث
    $leaves = array();
    $sql = "SELECT lvsID, empID, fullNmEmp, lvstyp, lvsReas, lvsDur, 
            TO_CHAR(lvsStrtDat, 'DD-MM-YYYY') AS start_date, 
            TO_CHAR(lvsEndDat, 'DD-MM-YYYY') AS end_date, 
            lvsStts
            FROM t_leaves";
    $stmt = oci_parse($conn, $sql);

    // تنفيذ الاستعلام
    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($leaves, $rows); // إضافة النتائج إلى مصفوفة
        }
        $result['leaves'] = $leaves; // إضافة البيانات إلى النتيجة
    }
    // إرسال النتيجة بتنسيق JSON
    echo json_encode($result);
}

elseif ($action == "insert") {



    $empID  = isset($_GET['empID']) ? $_GET['empID'] : "";
    $fullNmEmp  = isset($_GET['fullNmEmp']) ? $_GET['fullNmEmp'] : "";
    $lvstyp  = isset($_GET['lvstyp']) ? $_GET['lvstyp'] : "";
    $lvsReas = isset($_GET['lvsReas']) ? $_GET['lvsReas'] : "";
    $lvsDur = isset($_GET['lvsDur']) ? $_GET['lvsDur'] : "";
    $lvsStrtDat = isset($_GET['lvsStrtDat']) ? $_GET['lvsStrtDat'] : "";

    // التحقق من القيم الفارغة وتحديد قيم افتراضية
    if (empty($empID) || empty($fullNmEmp) || empty($lvstyp) || empty($lvsReas) || empty($lvsDur) || empty($lvsStrtDat)) {
        $result['status'] = 'error';
        $result['message'] = 'Missing required fields';
        echo json_encode( $result);
        exit;
    }

    // تحويل تاريخ البداية إلى كائن DateTime
    $startDate = new DateTime($lvsStrtDat);

    // إضافة المدة إلى تاريخ البداية
    $startDate->modify("+{$lvsDur} days");

    // الحصول على تاريخ النهاية كقيمة نصية
    $lvsEndDat = $startDate->format('Y-m-d');


    //التحقق من عدم استفاد الموضف من العطلة السنوية

     // إنشاء الاستعلام
    $sql = "INSERT INTO t_leaves (
                lvsID, 
                fullNmEmp, 
                lvstyp, 
                lvsReas, 
                lvsDur, 
                lvsStrtDat, 
                lvsEndDat, 
                empID
            ) VALUES (
                SEQ_LVS.nextval || '-' || EXTRACT(YEAR FROM SYSDATE),
                :paramFullName,
                :paramTypeLeave,
                :paramReasonLeave,
                :paramDurationLeave,
                TO_DATE(:paramStartDate, 'YYYY-MM-DD'),
                TO_DATE(:paramEndDate, 'YYYY-MM-DD'),
                :paramEmpID
            )";
    $stmt = oci_parse($conn, $sql);

    // ربط المتغيرات مع الاستعلام
    oci_bind_by_name($stmt, ':paramFullName', $fullNmEmp);
    oci_bind_by_name($stmt, ':paramTypeLeave', $lvstyp);
    oci_bind_by_name($stmt, ':paramReasonLeave', $lvsReas);
    oci_bind_by_name($stmt, ':paramDurationLeave', $lvsDur);
    oci_bind_by_name($stmt, ':paramStartDate', $lvsStrtDat);
    oci_bind_by_name($stmt, ':paramEndDate', $lvsEndDat);
    oci_bind_by_name($stmt, ':paramEmpID', $empID);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $result['status'] = 'success';
        $result['message'] = 'good';
    } else {
        $e = oci_error($stmt); // جلب معلومات الخطأ
        $result['status'] = 'error';
        $result['message'] = 'no';
        $result['error'] = $e['message']; // إرسال رسالة الخطأ للمتصفح
    }

    // إرجاع استجابة JSON صالحة دائمًا
    echo json_encode($result);


}

elseif ($action == 'update') {
    $id = isset($_GET['lvsID']) ? $_GET['lvsID'] : "";
    $typ = isset($_GET['lvstyp']) ? $_GET['lvstyp'] : "";
    $rsn = isset($_GET['lvsReas']) ? $_GET['lvsReas'] : "";
    $dur = isset($_GET['lvsDur']) ? $_GET['lvsDur'] : "";
    $strDat = isset($_GET['lvsStrtDat']) ? $_GET['lvsStrtDat'] : "";

    // التحقق من القيم الفارغة وتحديد قيم افتراضية
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
        $result["status"] = "success";
        $result["message"] = "Update leave successfully, end date updated.";
        echo json_encode($result);
    } else {
        $error = oci_error($stmt); // احصل على تفاصيل الخطأ إذا لزم الأمر
        $result["status"] = "error";
        $result["message"] = "Failed to update leave: " . $error['message'];
        echo json_encode($result);
    }
}
elseif($action == 'delete'){
    $id = "";
    isset($_GET['id']) ? $id = $_GET['id'] : $id = "" ;
    delete($conn, 'T_leaves', 'lvsid', $id);
}




?>
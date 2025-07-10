<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

include "../includes/functions/functions.php";

$action = test_input(isset($_GET['action']) )  ?  test_input($_GET['action']) :   ''; 


if ($action =="sector")
{
    $sectorId = htmlspecialchars($_POST['sectorId']);
        

    // استعلام لجلب الرتب المرتبطة بالقطاع
    $sqlRnk = "SELECT rnkID, rnkName FROM t_ranks WHERE secID = :sectorId";
    $stmtRnk = oci_parse($conn, $sqlRnk);
    oci_bind_by_name($stmtRnk, ":sectorId", $sectorId);

    oci_execute($stmtRnk);

    $options = '<option value="">Select Rank</option>';
    while ($rowRnk = oci_fetch_assoc($stmtRnk)) {
        $options .= '<option value="' . htmlspecialchars($rowRnk['RNKID']) . '">' . htmlspecialchars($rowRnk['RNKNAME']) . '</option>';
    }
    echo $options;
}

elseif ($action == "rank")
{
    $secID = $_GET['secID'];

    $sql = "SELECT RNKID, RNKNAME FROM t_ranks WHERE secID = :secID";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":secID", $secID);
    oci_execute($stmt);

    $options = '<option value="">اختر الرتبة</option>'; // خيار افتراضي

    while ($rowRnk = oci_fetch_assoc($stmt)) {
        $options .= '<option value="' . htmlspecialchars($rowRnk['RNKID']) . '">' . htmlspecialchars($rowRnk['RNKNAME']) . '</option>';
    }

    echo $options;
}

if ($action ==='countEmployees') 
{
    getCount($conn,'T_employees');
}
elseif($action ==='search')
{
    
    $val = isset($_GET['val']) ? $_GET['val'] : "";
    
      
    $employees = array();
    $sql = "SELECT 
        T_employees.empID,          
        T_employees.frstNmEmp,      
        T_employees.lstNmEmp,       
        T_employees.gndEmp,
        TO_CHAR(T_employees.empDatBrth, 'DD-MM-YYYY') AS empDatBrth ,     
        T_employees.munpBrth,       
        T_employees.sttBrth,        
        T_employees.NatEmp,        
        T_employees.socSecNum,     
        T_employees.BnkAccNum,      
        T_employees.natSerCrdNum,   
        T_employees.natIdnCrdNum,   
        T_employees.currAddrs,     
        T_employees.phoNum,        
        T_employees.emlEmp,        
        T_employees.famstt,         
        T_employees.husNm,          
        T_employees.husFmNm,        
        T_employees.numChd,         
        T_employees.workRel,       
        T_employees.yrApp,          
        T_employees.DepID,         
        T_employees.secID,          
        T_employees.rnkID,          
        T_Departments.depName,      
        T_Sectors.secName,          
        T_Ranks.rnkName          
        FROM 
        T_employees
        JOIN 
        T_Departments
        ON T_employees.DepID = T_Departments.DepID
        JOIN 
        T_Sectors
        ON T_employees.secID = T_Sectors.secID
        JOIN 
        T_Ranks
        ON T_employees.rnkID = T_Ranks.rnkID
        WHERE 
        T_employees.empID LIKE :searchParam || '%'
        OR (T_employees.frstNmEmp || ' ' || T_employees.lstNmEmp) LIKE :searchParam || '%'";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':searchParam', $val);
    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($employees, $rows);
        }
        $result['employees'] = $employees;
       
  }else{
    $e = oci_error();
  }
  echo json_encode($result);
  
    
}
elseif ($action === "read")
{  
   
    $employees = array();
    $sql = "SELECT 
    T_employees.empID,          
    T_employees.frstNmEmp,      
    T_employees.lstNmEmp,       
    T_employees.gndEmp,
    TO_CHAR(T_employees.empDatBrth, 'DD-MM-YYYY') AS empDatBrth ,     
    T_employees.munpBrth,       
    T_employees.sttBrth,        
    T_employees.NatEmp,        
    T_employees.socSecNum,     
    T_employees.BnkAccNum,      
    T_employees.natSerCrdNum,   
    T_employees.natIdnCrdNum,   
    T_employees.currAddrs,     
    T_employees.phoNum,        
    T_employees.emlEmp,        
    T_employees.famstt,         
    T_employees.husNm,          
    T_employees.husFmNm,        
    T_employees.numChd,         
    T_employees.workRel,       
    TO_CHAR(T_employees.yrApp, 'DD-MM-YYYY')AS yrApp,
    T_employees.DepID,         
    T_employees.secID,          
    T_employees.rnkID,          
    T_Departments.depName,      
    T_Sectors.secName,          
    T_Ranks.rnkName           
        FROM 
    T_employees
        JOIN 
    T_Departments
        ON T_employees.DepID = T_Departments.DepID
        JOIN 
    T_Sectors
        ON T_employees.secID = T_Sectors.secID
        JOIN 
    T_Ranks
        ON T_employees.rnkID = T_Ranks.rnkID
        ORDER BY empID DESC";
    $stmt = oci_parse($conn, $sql);
    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($employees, $rows);
        }
        $result['employees'] = $employees;
       
  }else{
    $e = oci_error();
  }
  echo json_encode($result);
}
elseif($action === "insert")
{
    $fNm  = isset($_GET['fNm']) ? $_GET['fNm'] : "";
    $lNm  = isset($_GET['lNm']) ? $_GET['lNm'] : "";
    $datBrth  = isset($_GET['datBrth']) ? $_GET['datBrth'] : "";
    $muncpBrth  = isset($_GET['muncpBrth']) ? $_GET['muncpBrth'] : "";
    $sttBrth  = isset($_GET['sttBrth']) ? $_GET['sttBrth'] : "";
    $idCrdNum =isset($_GET['idCrdNum']) ? $_GET['idCrdNum'] : "";
    $serCrdNum  = isset($_GET['serCrdNum']) ? $_GET['serCrdNum'] : ''; //
    $bnkAccNum  = isset($_GET['bnkAccNum']) ? $_GET['bnkAccNum'] : ""; 
    $socSecNum  = isset($_GET['socSecNum']) ? $_GET['socSecNum'] : ""; 
    $gnd  = isset($_GET['gnd']) ? $_GET['gnd'] : ""; 
    $addrs  = isset($_GET['addrs']) ? $_GET['addrs'] : ""; 
    $eml  = isset($_GET['eml']) ? $_GET['eml'] : "";  
    $phn  = isset($_GET['phn']) ? $_GET['phn'] : "";  
    $maritalStatus  = isset($_GET['maritalStatus']) ? $_GET['maritalStatus'] : "";  
    $husbNm  = isset($_GET['husbNm']) ? $_GET['husbNm'] : '';  //
    $husFmlyNm  = isset($_GET['husFmlyNm']) ? $_GET['husFmlyNm'] : ''; // 
    $numChld  = isset($_GET['numChld']) ? $_GET['numChld'] : '';  //
    $sctr  = isset($_GET['sctr']) ? $_GET['sctr'] : "";  
    $rnk  = isset($_GET['rnk']) ? $_GET['rnk'] : "";  
     $dept  = isset($_GET['dept']) ? $_GET['dept'] : "";  
    $wrkRlt  = isset($_GET['wrkRlt']) ? $_GET['wrkRlt'] : "";  
    

    
    if (empty($fNm) || empty($lNm) || empty($datBrth) || empty($muncpBrth) || empty($sttBrth) ||
        empty($idCrdNum)  || empty($bnkAccNum) ||  empty($socSecNum) ||
        empty($gnd) ||  empty($addrs) || empty($eml) ||  empty($phn) || empty($maritalStatus) ||  
        empty($sctr) ||   empty($rnk) ||  empty($dept) || empty($wrkRlt)) {
        $result["status"]= "error";
        $result["message"]= "Missing required fields.";
        echo json_encode($result);
        exit;
    }

     
    // select code department 
   $sql = "SELECT depCode FROM t_departments WHERE DepID = :paramDepID";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':paramDepID', $dept);

    if (oci_execute($stmt)) {
        $row = oci_fetch_assoc($stmt);
        $depCode = $row['DEPCODE']; // استخراج الكود
    }
   


    //$idCrdNum
    $checkIdCrdNumSQL = "SELECT COUNT(*) AS count FROM t_employees WHERE natIdnCrdNum = :id_Crd_Num";
    $checkIdCrdNumStmt = oci_parse($conn, $checkIdCrdNumSQL);
    oci_bind_by_name($checkIdCrdNumStmt, ':id_Crd_Num', $idCrdNum);
    oci_execute($checkIdCrdNumStmt);
    $IdCrdNumRow = oci_fetch_assoc($checkIdCrdNumStmt);
     //serCrdNum  
    $checkSerCrdNumSQL = "SELECT COUNT(*) AS count FROM t_employees WHERE natSerCrdNum = :ser_Crd_Num";
    $checkSerCrdNumStmt = oci_parse($conn, $checkSerCrdNumSQL);
    oci_bind_by_name($checkSerCrdNumStmt, ':ser_Crd_Num', $serCrdNum);
    oci_execute($checkSerCrdNumStmt);
    $SerCrdNumRow = oci_fetch_assoc($checkSerCrdNumStmt);
    // checkBnkAccNum
    $checkBnkAccNumSQL = "SELECT COUNT(*) AS count FROM t_employees WHERE BnkAccNum = :bnk_Acc_Num";
    $checkBnkAccNumStmt = oci_parse($conn, $checkBnkAccNumSQL);
    oci_bind_by_name($checkBnkAccNumStmt, ':bnk_Acc_Num', $bnkAccNum);
    oci_execute($checkBnkAccNumStmt);
    $BnkAccNumRow = oci_fetch_assoc($checkBnkAccNumStmt);
    // checkSocSecNumSQL
    $checkSocSecNumSQL = "SELECT COUNT(*) AS count FROM t_employees WHERE socSecNum = :soc_Sec_Num";
    $checkSocSecNumStmt = oci_parse($conn, $checkSocSecNumSQL);
    oci_bind_by_name($checkSocSecNumStmt, ':soc_Sec_Num', $socSecNum);
    oci_execute($checkSocSecNumStmt);
    $SocSecNumRow = oci_fetch_assoc($checkSocSecNumStmt);
    //checkPhone
    $checkPhoneSQL = " SELECT COUNT(*) AS count FROM t_employees WHERE phoNum  = :Ph_num_emp";
    $checkPhoneStmt = oci_parse($conn, $checkPhoneSQL);
    oci_bind_by_name($checkPhoneStmt, ':Ph_num_emp', $phn);
    oci_execute($checkPhoneStmt);
    $phoneRow = oci_fetch_assoc($checkPhoneStmt);
    // checkEmail
    $checkEmailSQL = " SELECT COUNT(*) AS count FROM t_employees WHERE emlEmp  = :Eml_emp";
    $checkEmailStmt = oci_parse($conn, $checkEmailSQL);
    oci_bind_by_name($checkEmailStmt, ':Eml_emp', $eml);
    oci_execute($checkEmailStmt);
    $EmailRow = oci_fetch_assoc($checkEmailStmt);

    if($phoneRow['COUNT']> 0)
    {
        $result["statusInput"]= "error";
        $result["messageInput"]= "The phone number already exists in the database.";
        echo json_encode($result);
    }
    elseif($EmailRow['COUNT']> 0)
    {
        $result["status"]= "error";
        $result["message"]= "Email already exists in database.";
        echo json_encode($result);
    }
    elseif($IdCrdNumRow['COUNT']> 0)
    {
        $result["status"]= "error";
        $result["message"]= "The identification card number already exists in the database";
        echo json_encode($result);
    }
    elseif($SerCrdNumRow['COUNT']> 0)
    {
        $result["status"]= "error";
        $result["message"]= "The service card number already exists in the database.";
        echo json_encode($result);
    }
    elseif($BnkAccNumRow['COUNT']> 0)
    {
        $result["status"]= "error";
        $result["message"]= "The bank account number already exists in the database.";
        echo json_encode($result);
    }
    elseif($SocSecNumRow['COUNT']> 0)
    {
        $result["status"]= "error";
        $result["message"]= "social security number already exists in the database.";
        echo json_encode($result);

    }
    else{
        $sql = "INSERT INTO t_employees (
            empID, frstNmEmp, lstNmEmp, empDatBrth, gndEmp, munpBrth, sttBrth, socSecNum, BnkAccNum,
            natSerCrdNum, natIdnCrdNum, currAddrs, phoNum, emlEmp, famstt, husNm, husFmNm, numChd, 
            workRel, DepID, secID, rnkID) 
        VALUES (
            SEQ_EMP.nextval || '-' || :depCode || '-' || EXTRACT(YEAR FROM SYSDATE),
            :FrstNmEmp, :LstNmEmp, TO_DATE(:EmpDatBrth, 'DD-MM-YY'), :GndEmp, :munpBrth, :sttBrth, 
            :socSecNum, :BnkAccNum, :natSerCrdNum, :natIdnCrdNum, :currAddrs, :phoNum, :emlEmp, 
            :famstt, :husNm, :husFmNm, :numChd, :workRel, :DepID, :secID, :rnkID)";
        
        $insertEmpStmt = oci_parse($conn, $sql);
        
        oci_bind_by_name($insertEmpStmt, ':depCode', $depCode);
        oci_bind_by_name($insertEmpStmt, ':FrstNmEmp', $fNm);
        oci_bind_by_name($insertEmpStmt, ':LstNmEmp', $lNm);
        oci_bind_by_name($insertEmpStmt, ':EmpDatBrth', $datBrth);
        oci_bind_by_name($insertEmpStmt, ':GndEmp', $gnd);
        oci_bind_by_name($insertEmpStmt, ':munpBrth', $muncpBrth);
        oci_bind_by_name($insertEmpStmt, ':sttBrth', $sttBrth);
        oci_bind_by_name($insertEmpStmt, ':socSecNum', $socSecNum);
        oci_bind_by_name($insertEmpStmt, ':BnkAccNum', $bnkAccNum);
        oci_bind_by_name($insertEmpStmt, ':natSerCrdNum', $serCrdNum);
        oci_bind_by_name($insertEmpStmt, ':natIdnCrdNum', $idCrdNum);
        oci_bind_by_name($insertEmpStmt, ':currAddrs', $addrs);
        oci_bind_by_name($insertEmpStmt, ':phoNum', $phn);
        oci_bind_by_name($insertEmpStmt, ':emlEmp', $eml);
        oci_bind_by_name($insertEmpStmt, ':famstt', $maritalStatus);
        oci_bind_by_name($insertEmpStmt, ':husNm', $husbNm);
        oci_bind_by_name($insertEmpStmt, ':husFmNm', $husFmlyNm);
        oci_bind_by_name($insertEmpStmt, ':numChd', $numChld);
        oci_bind_by_name($insertEmpStmt, ':workRel', $wrkRlt);
        oci_bind_by_name($insertEmpStmt, ':DepID', $dept);
        oci_bind_by_name($insertEmpStmt, ':secID', $sctr);
        oci_bind_by_name($insertEmpStmt, ':rnkID', $rnk);
        
        if(oci_execute($insertEmpStmt)){
            //__________________________________________________________________________________

            $sqlGetEmpID = "SELECT SEQ_EMP.CURRVAL || '-' || :depCode || '-' || EXTRACT(YEAR FROM SYSDATE) AS EMPID FROM dual";
            $stmtGetEmpID = oci_parse($conn, $sqlGetEmpID);
            oci_bind_by_name($stmtGetEmpID, ':depCode', $depCode);
            oci_execute($stmtGetEmpID);
            $row = oci_fetch_assoc($stmtGetEmpID);
            $empID = $row['EMPID'];
            
            // إنشاء cerID
            $sqlGetCerID = "SELECT SEQ_CER.NEXTVAL || '-' || SEQ_EMP.CURRVAL AS CERID FROM dual";
            $stmtGetCerID = oci_parse($conn, $sqlGetCerID);
            oci_execute($stmtGetCerID);
            $rowCer = oci_fetch_assoc($stmtGetCerID);
            $cerID = $rowCer['CERID'];
            
            // إدراج بيانات الشهادة في T_Emp_certificates
            $sqlInsertCert = "INSERT INTO T_Emp_certificates (cerID, empID) VALUES (:cerID, :empID)";
            $stmtInsertCert = oci_parse($conn, $sqlInsertCert);
            oci_bind_by_name($stmtInsertCert, ':cerID', $cerID);
            oci_bind_by_name($stmtInsertCert, ':empID', $empID);
   
            oci_execute($stmtInsertCert);
            oci_commit($conn);
            //__________________________________________________________________________________
            $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'employee Insert', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);

            //__________________________________________________________________________________
            $result["status"]= "success";
            $result["message"]= "Employee added successfully";
            echo json_encode($result);
        }else{
            $error = oci_error();
            $result["status"]= "error";
            $result["message"]= "Employee was not added successfully." . $error['message'];
            echo json_encode($result);
        }
    }
}

elseif($action === "update"){
    $id  = isset($_GET['id']) ? $_GET['id'] : "";
    $fNm  = isset($_GET['fNm']) ? $_GET['fNm'] : "";
    $lNm  = isset($_GET['lNm']) ? $_GET['lNm'] : "";
    $datBrth  = isset($_GET['datBrth']) ? $_GET['datBrth'] : "";
    $muncpBrth  = isset($_GET['muncpBrth']) ? $_GET['muncpBrth'] : "";
    $sttBrth  = isset($_GET['sttBrth']) ? $_GET['sttBrth'] : "";
    $idCrdNum =isset($_GET['idCrdNum']) ? $_GET['idCrdNum'] : "";
    $serCrdNum  = isset($_GET['serCrdNum']) ? $_GET['serCrdNum'] : ''; //
    $bnkAccNum  = isset($_GET['bnkAccNum']) ? $_GET['bnkAccNum'] : ""; 
    $socSecNum  = isset($_GET['socSecNum']) ? $_GET['socSecNum'] : ""; 
    $gnd  = isset($_GET['gnd']) ? $_GET['gnd'] : ""; 
    $addrs  = isset($_GET['addrs']) ? $_GET['addrs'] : ""; 
    $eml  = isset($_GET['eml']) ? $_GET['eml'] : "";  
    $phn  = isset($_GET['phn']) ? $_GET['phn'] : "";  
    $maritalStatus  = isset($_GET['maritalStatus']) ? $_GET['maritalStatus'] : "";  
    $husbNm  = isset($_GET['husbNm']) ? $_GET['husbNm'] : '';  //
    $husFmlyNm  = isset($_GET['husFmlyNm']) ? $_GET['husFmlyNm'] : ''; // 
    $numChld  = isset($_GET['numChld']) ? $_GET['numChld'] : 0;  //
    $sctr  = isset($_GET['sctr']) ? $_GET['sctr'] : "";  
    $rnk  = isset($_GET['rnk']) ? $_GET['rnk'] : "";  
     $dept  = isset($_GET['dept']) ? $_GET['dept'] : "";  
    $wrkRlt  = isset($_GET['wrkRlt']) ? $_GET['wrkRlt'] : "";

    $SQL = "UPDATE T_employees SET frstNmEmp = :paramfrstNmEmp,
                              lstNmEmp = :paramlstNmEmp,
                              gndEmp = :paramgndEmp,
                              empDatBrth = TO_DATE(:paramempDatBrth, 'YYYY-MM-DD'),
                              munpBrth = :parammunpBrth,
                              sttBrth = :paramsttBrth,
                              socSecNum = :paramsocSecNum,
                              BnkAccNum = :paramBnkAccNum,
                              natSerCrdNum = :paramnatSerCrdNum,
                              currAddrs = :paramcurrAddrs,
                              phoNum = :paramphoNum,
                              emlEmp = :paramemlEmp,
                              famstt = :paramfamstt,
                              husNm = :paramhusNm,
                              husFmNm = :paramhusFmNm,
                              numChd = :paramnumChd,
                              workRel = :paramworkRel,
                              DepID = :paramDepID,
                              secID = :paramsecID,
                              rnkID = :paramrnkID
                             WHERE empID = :paramId";
    $stmt = oci_parse($conn, $SQL); 
    oci_bind_by_name($stmt, ':paramfrstNmEmp', $fNm);
    oci_bind_by_name($stmt, ':paramlstNmEmp', $lNm);
    oci_bind_by_name($stmt, ':paramgndEmp', $gnd);
    oci_bind_by_name($stmt, ':paramempDatBrth', $datBrth);
    oci_bind_by_name($stmt, ':parammunpBrth', $muncpBrth);
    oci_bind_by_name($stmt, ':paramsttBrth', $sttBrth);
    oci_bind_by_name($stmt, ':paramsocSecNum', $socSecNum);
    oci_bind_by_name($stmt, ':paramBnkAccNum', $bnkAccNum);
    oci_bind_by_name($stmt, ':paramnatSerCrdNum', $serCrdNum);
    oci_bind_by_name($stmt, ':paramcurrAddrs', $addrs);
    oci_bind_by_name($stmt, ':paramphoNum', $phn);
    oci_bind_by_name($stmt, ':paramemlEmp', $eml);
    oci_bind_by_name($stmt, ':paramfamstt', $maritalStatus);
    oci_bind_by_name($stmt, ':paramhusNm', $husbNm);
    oci_bind_by_name($stmt, ':paramhusFmNm', $husFmlyNm);
    oci_bind_by_name($stmt, ':paramnumChd', $numChld);
    oci_bind_by_name($stmt, ':paramworkRel', $wrkRlt);
    oci_bind_by_name($stmt, ':paramDepID', $dept);
    oci_bind_by_name($stmt, ':paramsecID', $sctr);
    oci_bind_by_name($stmt, ':paramrnkID', $rnk);
    oci_bind_by_name($stmt, ':paramId', $id);
    $sqlUpdTrnsDep = "UPDATE T_Transfers SET depID = :paramDepID WHERE  empID = :paramSelEmpID";
    $stmtUpdTrnsDep =oci_parse($conn, $sqlUpdTrnsDep);
    oci_bind_by_name($stmtUpdTrnsDep, ':paramSelEmpID', $id);
    oci_bind_by_name($stmtUpdTrnsDep, ':paramDepID', $dept);
    if (oci_execute($stmtUpdTrnsDep)) {
        oci_commit($conn);
    }
    if (oci_execute($stmt)) {
        $adminID =   $_SESSION['id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
            VALUES (SEQ_LOG.NEXTVAL, :adminID, 'employee update', SYSTIMESTAMP, :ipAddress)";
            $logStmt = oci_parse($conn, $logSQL);
            oci_bind_by_name($logStmt, ':adminID', $adminID);
            oci_bind_by_name($logStmt, ':ipAddress', $ip);
            oci_execute($logStmt);
            oci_commit($conn);
            $result["status"]= "success";
            $result["message"]= "update Employee successfully";
            echo json_encode($result);
    } else {
        $result["status"]= "error";
        $result["message"]= "Employee was not updeted successfully." ;
        echo json_encode($result);
    }



}

elseif ($action === 'delete') {
    $adminID = $_SESSION['id']; // رقم المستخدم الذي يقوم بالحذف
    $empID  = isset($_GET['id']) ? $_GET['id'] : "";
    $ip = $_SERVER['REMOTE_ADDR'];

    //  **التحقق مما إذا كان المستخدم يحاول حذف سجله كعامل**
    $sqlCheck = "SELECT COUNT(*) AS CNT FROM t_users WHERE empID = :empID AND usrID = :adminID";
    $stmtCheck = oci_parse($conn, $sqlCheck);
    oci_bind_by_name($stmtCheck, ':empID', $empID);
    oci_bind_by_name($stmtCheck, ':adminID', $adminID);
    oci_execute($stmtCheck);
    $row = oci_fetch_assoc($stmtCheck);

    if ($row['CNT'] > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "You cannot delete your own record."
        ]);
        exit; // إيقاف تنفيذ الحذف
    }

    //  1. حذف الموظف
    $SQL = "DELETE FROM T_employees WHERE empID = :empID";
    $stmt = oci_parse($conn, $SQL);
    oci_bind_by_name($stmt, ':empID', $empID);

    if (oci_execute($stmt)) {
        oci_commit($conn);

        //  2. تسجيل العملية في `T_log_active`
        $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                    VALUES (SEQ_LOG.NEXTVAL, :adminID, 'employee Delete', SYSTIMESTAMP, :ipAddress )";
        
        $logStmt = oci_parse($conn, $logSQL);
        oci_bind_by_name($logStmt, ':adminID', $adminID);
        oci_bind_by_name($logStmt, ':ipAddress', $ip);

        if (oci_execute($logStmt)) {
            oci_commit($conn);
            $result["status"] = "success";
            $result["message"] = "Employee deleted successfully.";
        }
    }  
    echo json_encode($result);
}

   
// select all Employee Certificate
elseif($action == 'empCert'){
    $Certificate = array();
    $sql ="SELECT  (e.frstNmEmp|| ' ' ||e.lstNmEmp) AS full_name,
    c.cerID, c.empID FROM T_Emp_certificates c
    JOIN T_employees e ON   c.empID = e.empID ORDER BY c.cerID DESC";
    $stmt = oci_parse($conn, $sql);
    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($Certificate, $rows);
        }
    }  
     $result['Certificates'] = $Certificate;
     echo json_encode($result);
}
?>
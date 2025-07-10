<?php

include '../includes/libraris/TCPDF/tcpdf.php';

include '../connect.php';

$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 
$id =  isset($_GET['id']) ?  $_GET['id'] :   '';

if ($action == "print") {
    $employees = array();
    $sql = "SELECT 
    emp.empID,
    emp.frstNmEmp,
    emp.lstNmEmp,
    TO_CHAR(emp.empDatBrth,'DD-MM-YYYY') AS empDatBrth,
    
    TO_CHAR(emp.yrApp,'DD-MM-YYYY') AS yrApp,

    rnk.rnkName,
    dep.depName
    FROM  T_employees emp
    JOIN  t_ranks rnk ON emp.rnkID = rnk.rnkID
    JOIN   T_Departments dep ON emp.DepID = dep.DepID
    WHERE empID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($employees, $rows);
        }
    }
      json_encode(['employee' => $employees]);

}

// التحقق مما إذا كانت هناك بيانات مسترجعة
if (count($employees) > 0) {
    $employee = $employees[0]; 
} else {
    die("لم يتم العثور على بيانات لهذا المعرف.");
}

// إنشاء كائن TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setRTL(true);
$pdf->SetFont('dejavusans', '', 11);
$pdf->AddPage();

// إنشاء HTML مع البيانات المسترجعة
$html = ' 
<h1>People\'s Democratic Republic of Algeria</h1>

<h3 style="text-align:left;">State: Souk Ahras</h3>
<h3 style="text-align:left;">Daira: Madawrouch</h3>
<h3 style="text-align:left;">Municipality: Madawrouch</h3>
<h3 style="text-align:left;">Document Number: ' . $employee['EMPID'] . '</h3>

<h2 style="text-align:center;"> Appointment Decision </h2>

<table cellpadding="20" >
    <tr>
        <td style="text-align:right; font-weight:bold;  ">الاسم:</td>
        <td style="text-align:center;  ">' . $employee['FRSTNMEMP'] . '</td>
        <td style="text-align:left; ">Name:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;">اللقب:</td>
        <td style="text-align:center;">' . $employee['LSTNMEMP'] . '</td>
        <td style="text-align:left;">Last name:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">تاريخ الميلاد:</td>
        <td style="text-align:center;">' . $employee['EMPDATBRTH'] . '</td>
        <td style="text-align:left;">Date of birth Employee:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">الرتبة:</td>
        <td style="text-align:center;">' . $employee['RNKNAME'] . '</td>
        <td style="text-align:left;">Rank:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">المصلحة:</td>
        <td style="text-align:center;">' . $employee['DEPNAME'] . '</td>
        <td style="text-align:left;">department:</td>
    </tr>
    <tr>
        <td style="text-align:right; font-weight:bold;">إبتداء من:</td>
        <td style="text-align:center;">' . $employee['YRAPP'] . '</td>
        <td style="text-align:left;">Start date:</td>
    </tr>

    
</table>';

// طباعة HTML داخل PDF
$pdf->writeHTML( $html, true, false, true, false, '');

// حفظ أو عرض الملف
$pdf->Output('leave_certificate.pdf', 'I');
?>

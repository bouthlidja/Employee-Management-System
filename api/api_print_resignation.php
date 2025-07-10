<?php
header('Content-Type: application/json');
include '../includes/libraris/TCPDF/tcpdf.php';
include '../connect.php';

$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 
$id =  isset($_GET['id']) ?  $_GET['id'] :   '';

if ($action == "print") {
    $resignations = array();
    $sql = "  SELECT 
        rs.resgID, 
        emp.frstNmEmp,
        emp.lstNmEmp,
        rnk.rnkName,
        rs.resgRreas,
        TO_CHAR(rs.empConfDat, 'DD-MM-YYYY') AS empConfDat,
        TO_CHAR(rs.resgEffDat, 'DD-MM-YYYY') AS resgEffDat
        
        FROM 
            T_resignations rs
        JOIN 
            T_employees emp ON emp.empID = rs.empID
            JOIN 
            T_Ranks rnk ON emp.rnkID =rnk.rnkID
        
        WHERE rs.resgID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($resignations, $rows);
        }
    }
    
     json_encode(['resignation' => $resignations]);

}

// التحقق مما إذا كانت هناك بيانات مسترجعة
if (count($resignations) > 0) {
    $resignation = $resignations[0]; //  أول سجل
} else {
    die("No data found for this ID");
}

// إنشاء كائن TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setRTL(true);
$pdf->SetFont('dejavusans', '', 11);
$pdf->AddPage();

 
 


// إنشاء HTML مع البيانات المسترجعة
$html = ' 
<h1 style="text-align:center;">People\'s Democratic Republic of Algeria</h1>

<h3 style="text-align:left;">State: Souk Ahras</h3>
<h3 style="text-align:left;">Daira: Madawrouch</h3>
<h3 style="text-align:left;">Municipality: Madawrouch</h3>
<h3 style="text-align:left;">Document Number: ' . $resignation['RESGID'] . '</h3>

<h2 style="text-align:center;">Resignation Decision</h2>

<table cellpadding="5" >
    <tr >
        <td style="text-align:right; font-weight:bold; ">الاسم:</td>
        <td style="text-align:center;  ">' . $resignation['FRSTNMEMP'] . '</td>
        <td style="text-align:left; ">Name:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;">اللقب:</td>
        <td style="text-align:center;">' . $resignation['LSTNMEMP'] . '</td>
        <td style="text-align:left;">Last name:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> الرتبة:</td>
        <td style="text-align:center;">' . $resignation['RNKNAME'] . '</td>
        <td style="text-align:left;">Rank:</td>
    </tr>
    <tr>
        <td style="text-align:right; font-weight:bold;"> السبب:</td>
        <td style="text-align:center;">' . $resignation['RESGRREAS'] . '</td>
        <td style="text-align:left;">Reason:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ تقديم طلب الاستقالة:</td>
        <td style="text-align:center;">' . $resignation['EMPCONFDAT'] . '</td>
        <td style="text-align:left;">Resignation Request Submission Date:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ سريان الاستقالة:</td>
        <td style="text-align:center;">' . $resignation['RESGEFFDAT'] . '</td>
        <td style="text-align:left;">Resignation Effective Date:</td>
    </tr>

   
</table>';

// طباعة HTML داخل PDF
$pdf->writeHTML($html, true, false, true, false, '');

// حفظ أو عرض الملف
$pdf->Output('leave_certificate.pdf', 'I');
?>

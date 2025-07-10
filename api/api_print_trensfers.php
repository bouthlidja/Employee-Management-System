<?php
header('Content-Type: application/json');
include '../includes/libraris/TCPDF/tcpdf.php';
include '../connect.php';


$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 
$id =  isset($_GET['id']) ?  $_GET['id'] :   '';

if ($action == "print") {
    $trensfers = array();
    $sql = " SELECT 
        tr.traID, 
        emp.frstNmEmp,
        emp.lstNmEmp,
        rnk.rnkName,
        tr.currWrkp,
        dep.depName,
        TO_CHAR(tr.orgDepAppDat, 'DD-MM-YYYY') AS datOrgDept,
        TO_CHAR(tr.recDepAppDat, 'DD-MM-YYYY') AS recDepAppDat,   
        TO_CHAR(tr.newWrkStrDat, 'DD-MM-YYYY') AS newWrkStrDat
        FROM 
            t_transfers tr
        JOIN 
            T_employees emp ON emp.empID = tr.empID
            JOIN 
            T_Ranks rnk ON emp.rnkID =rnk.rnkID
        JOIN 
        T_departments dep ON dep.depID = tr.depID
        WHERE tr.traID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($trensfers, $rows);
        }
    }
     json_encode(['trensfer' => $trensfers]);

}

// التحقق مما إذا كانت هناك بيانات مسترجعة
if (count($trensfers) > 0) {
    $trensfer = $trensfers[0]; //  أول سجل
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
<h3 style="text-align:left;">Document Number: ' . $trensfer['TRAID'] . '</h3>

<h2 style="text-align:center;">Transfer Decision</h2>

<table cellpadding="5" >
    <tr>
        <td style="text-align:right; font-weight:bold; border-bottom: 1px solid #000;">الاسم:</td>
        <td style="text-align:center; border-bottom: 1px solid #000;">' . $trensfer['FRSTNMEMP'] . '</td>
        <td style="text-align:left; border-bottom: 1px solid #000;">Name:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;">اللقب:</td>
        <td style="text-align:center;">' . $trensfer['LSTNMEMP'] . '</td>
        <td style="text-align:left;">Last name:</td>
    </tr>



    <tr>
        <td style="text-align:right; font-weight:bold;"> الرتبة:</td>
        <td style="text-align:center;">' . $trensfer['RNKNAME'] . '</td>
        <td style="text-align:left;">Rank:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> مكان العمل الجديد:</td>
        <td style="text-align:center;">' . $trensfer['CURRWRKP'] . '</td>
        <td style="text-align:left;">Current Workplace:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ موفقة الإدارة الاصلية:</td>
        <td style="text-align:center;">' . $trensfer['DATORGDEPT'] . '</td>
        <td style="text-align:left;">Original Department Approval Date:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> مكان العمل الجديد:</td>
        <td style="text-align:center;">' . $trensfer['DEPNAME'] . '</td>
        <td style="text-align:left;">New Workplace:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ موفقة الإدارة الجديدة:</td>
        <td style="text-align:center;">' . $trensfer['RECDEPAPPDAT'] . '</td>
        <td style="text-align:left;">Receiving Department Approval Date:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">تاريخ بدأ العمل في المكان الجديد:</td>
        <td style="text-align:center;">' . $trensfer['NEWWRKSTRDAT'] . '</td>
        <td style="text-align:left;">Receiving Department Approval Date:</td>
    </tr>
</table>';

// طباعة HTML داخل PDF
$pdf->writeHTML($html, true, false, true, false, '');

// حفظ أو عرض الملف
$pdf->Output('leave_certificate.pdf', 'I');
?>

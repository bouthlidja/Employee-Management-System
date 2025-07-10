<?php
header('Content-Type: application/json');
include '../includes/libraris/TCPDF/tcpdf.php';
include '../connect.php';


 
$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 
$id =  isset($_GET['id']) ?  $_GET['id'] :   '';

if ($action == "print") {
    $retirements = array();
    $sql = " SELECT 
    ret.retID,
    emp.frstNmEmp,
    emp.lstNmEmp,
    rnk.rnkName,
    dep.depName,
    ret.retReas,
    TO_CHAR(ret.reqDat, 'DD-MM-YYYY') AS reqDat,
    TO_CHAR(ret.appDat, 'DD-MM-YYYY') AS appDat,
    TO_CHAR(emp.yrApp, 'DD-MM-YYYY') AS yrApp,
    
    (EXTRACT(YEAR FROM ret.reqDat) - EXTRACT(YEAR FROM emp.yrApp)) AS years_of_service
    FROM T_Retirement ret
    JOIN T_employees emp ON emp.empID = ret.empID
    JOIN T_Ranks rnk ON emp.rnkID = rnk.rnkID
    JOIN T_Departments dep ON dep.DepID = emp.DepID
    WHERE ret.retID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($retirements, $rows);
        }
    }
 
       json_encode(['retirement' => $retirements]);

}

// //التحقق مما إذا كانت هناك بيانات مسترجعة
if (count($retirements) > 0) {
    $retirement = $retirements[0]; //  أول سجل
} else {
    die("No data found for this ID");
}

//إنشاء كائن TCPDF
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
<h3 style="text-align:left;">Document Number: ' . $retirement['RETID'] . '</h3>

<h2 style="text-align:center;">Transfer Decision</h2>

<table cellpadding="5" >
    <tr>
        <td style="text-align:right; font-weight:bold; border-bottom: 1px solid #000;">الاسم:</td>
        <td style="text-align:center; border-bottom: 1px solid #000;">' . $retirement['FRSTNMEMP'] . '</td>
        <td style="text-align:left; border-bottom: 1px solid #000;">Name:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;">اللقب:</td>
        <td style="text-align:center;">' . $retirement['LSTNMEMP'] . '</td>
        <td style="text-align:left;">Last name:</td>
    </tr>



    <tr>
        <td style="text-align:right; font-weight:bold;"> الرتبة:</td>
        <td style="text-align:center;">' . $retirement['RNKNAME'] . '</td>
        <td style="text-align:left;">Rank:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">المصلحة:</td>
        <td style="text-align:center;">' . $retirement['DEPNAME'] . '</td>
        <td style="text-align:left;">Department:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">تاريخ التعيين:</td>
        <td style="text-align:center;">' . $retirement['YRAPP'] . '</td>
        <td style="text-align:left;">Date of appointment:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">سنوات الخدمة :</td>
        <td style="text-align:center;">' . $retirement['YEARS_OF_SERVICE'] . '</td>
        <td style="text-align:left;">Years of service:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">   السبب:</td>
        <td style="text-align:center;">' . $retirement['RETREAS'] . '</td>
        <td style="text-align:left;">Reason:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">    تاريخ تقديم الطلب :</td>
        <td style="text-align:center;">' . $retirement['REQDAT'] . '</td>
        <td style="text-align:left;">date application request </td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ الموافقة:</td>
        <td style="text-align:center;">' . $retirement['APPDAT'] . '</td>
        <td style="text-align:left;">Approval date:</td>
    </tr>

     
</table>';

// طباعة HTML داخل PDF
$pdf->writeHTML($html, true, false, true, false, '');

// حفظ أو عرض الملف
$pdf->Output('leave_certificate.pdf', 'I');
?>

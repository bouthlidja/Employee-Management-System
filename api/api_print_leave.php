<?php
header('Content-Type: application/json');
include '../includes/libraris/TCPDF/tcpdf.php';
include '../connect.php';


 
$action = isset($_GET['action']) ?  $_GET['action'] :   ''; 
$id =  isset($_GET['id']) ?  $_GET['id'] :   '';

if ($action == "print") {
    $leaves = array();
    $sql = "SELECT 
    lev.lvsID,  
    emp.frstNmEmp, 
    emp.lstNmEmp, 
    TO_CHAR(emp.empDatBrth,'DD-MM-YYYY') AS empDatBrth,
    lev.lvstyp, 
    lev.lvsReas, 
    lev.lvsDur, 
    TO_CHAR(lev.lvsStrtDat, 'DD-MM-YYYY') AS start_date, 
    TO_CHAR(lev.lvsEndDat, 'DD-MM-YYYY') AS end_date,
    rnk.rnkName  
 
    FROM t_leaves lev
    JOIN T_employees emp ON emp.empID = lev.empID
    JOIN T_ranks rnk ON emp.rnkID = rnk.rnkID   
    WHERE lev.lvsID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        while ($rows = oci_fetch_assoc($stmt)) {
            array_push($leaves, $rows);
        }
    }
    json_encode(['leaves' => $leaves]);

}

 
if (count($leaves) > 0) {
    $leave = $leaves[0]; //  أول سجل
} else {
    die("No data found for this ID .");
}

 
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setRTL(true);
$pdf->SetFont('dejavusans', '', 11);
$pdf->AddPage();

 

 
$html = ' 
<h1 style="text-align:center;">People\'s Democratic Republic of Algeria</h1>

<h3 style="text-align:left;">State: Souk Ahras</h3>
<h3 style="text-align:left;">Daira: Madawrouch</h3>
<h3 style="text-align:left;">Municipality: Madawrouch</h3>
<h3 style="text-align:left;">Document Number: ' . $leave['LVSID'] . '</h3>

<h2 style="text-align:center;">Leave Certificate</h2>

<table cellpadding="5" >
    <tr>
        <td style="text-align:right; font-weight:bold; border-bottom: 1px solid #000;">الاسم:</td>
        <td style="text-align:center; border-bottom: 1px solid #000;">' . $leave['FRSTNMEMP'] . '</td>
        <td style="text-align:left; border-bottom: 1px solid #000;">Name:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;">اللقب:</td>
        <td style="text-align:center;">' . $leave['LSTNMEMP'] . '</td>
        <td style="text-align:left;">Last name:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">تاريخ الميلاد:</td>
        <td style="text-align:center;">' . $leave['EMPDATBRTH'] . '</td>
        <td style="text-align:left;">Date of birth Employee:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> الرتبة:</td>
        <td style="text-align:center;">' . $leave['RNKNAME'] . '</td>
        <td style="text-align:left;">Rank:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> النوع:</td>
        <td style="text-align:center;">' . $leave['LVSTYP'] . '</td>
        <td style="text-align:left;">type:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> السبب:</td>
        <td style="text-align:center;">' . $leave['LVSREAS'] . '</td>
        <td style="text-align:left;">The reason:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;"> المدة:</td>
        <td style="text-align:center;">' . $leave['LVSDUR'] . '</td>
        <td style="text-align:left;">The duration:</td>
    </tr>

     <tr>
        <td style="text-align:right; font-weight:bold;"> إبتدا من:</td>
        <td style="text-align:center;">' . $leave['START_DATE'] . '</td>
        <td style="text-align:left;">Start date:</td>
    </tr>

    <tr>
        <td style="text-align:right; font-weight:bold;">  الى غاية:</td>
        <td style="text-align:center;">' . $leave['END_DATE'] . '</td>
        <td style="text-align:left;">End date:</td>
    </tr>
</table>';

 
$pdf->writeHTML($html, true, false, true, false, '');

 
$pdf->Output('leave_certificate.pdf', 'I');
?>

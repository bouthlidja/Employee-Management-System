<?php
include '../includes/libraris/TCPDF/tcpdf.php';
include '../connect.php';

$action = isset($_GET['action']) ? $_GET['action'] : ''; 
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($action == "print") {
    $Certificate = [];
    $sql = "SELECT e.frstNmEmp, e.lstNmEmp, e.empDatBrth, e.munpBrth, e.yrApp, e.workRel, 
                   r.rnkName, c.cerID, c.empID 
            FROM T_Emp_certificates c
            JOIN T_employees e ON c.empID = e.empID
            JOIN T_Ranks r ON r.rnkID = e.rnkID 
            WHERE c.cerID = :cerID";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':cerID', $id);
    
    if (oci_execute($stmt)) {
        while ($row = oci_fetch_assoc($stmt)) {
            $Certificate[] = $row; // تخزين النتائج في مصفوفة
        }
    }

     json_encode(['Certificates' => $Certificate]);

    // التحقق مما إذا تم العثور على بيانات
    if (count($Certificate) > 0) {
        $Certificate = $Certificate[0]; //  أول سجل
    } else {
        die("No data found for this ID");
    }

    // إنشاء ملف PDF باستخدام TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->setRTL(true);
    $pdf->SetFont('dejavusans', '', 11);
    $pdf->AddPage();

    // إضافة المحتوى إلى PDF
    $html = ' 
        <h1>People\'s Democratic Republic of Algeria</h1>
        <h3 style="text-align:left;">State: Souk Ahras</h3>
        <h3 style="text-align:left;">Daira: Madawrouch</h3>
        <h3 style="text-align:left;">Municipality: Madawrouch</h3>
        <h3 style="text-align:left;">Document Number: ' . $Certificate['CERID'] . '</h3>
        <h2 style="text-align:center;"> Employment Certificate </h2>
        
        <table cellpadding="20" >
            <tr>
                <td style="text-align:right; font-weight:bold;  ">الاسم:</td>
                <td style="text-align:center;  ">' . $Certificate['FRSTNMEMP'] .'</td>
                <td style="text-align:left; ">Name:</td>
            </tr>

            <tr>
                <td style="text-align:right; font-weight:bold;">اللقب:</td>
                <td style="text-align:center;">' . $Certificate['LSTNMEMP'] . '</td>
                <td style="text-align:left;">Last name:</td>
            </tr>

            <tr>
                <td style="text-align:right; font-weight:bold;">تاريخ الميلاد:</td>
                <td style="text-align:center;">' . $Certificate['EMPDATBRTH'] . " " .$Certificate['MUNPBRTH']. '</td>
                <td style="text-align:left;">Date of birth Employee:</td>
            </tr>

            <tr>
                <td style="text-align:right; font-weight:bold;">الرتبة:</td>
                <td style="text-align:center;">' . $Certificate['RNKNAME'] . '</td>
                <td style="text-align:left;">Rank:</td>
            </tr>

            <tr>
        <td style="text-align:right; font-weight:bold;"> تاريخ تعيين:</td>
        <td style="text-align:center;">' . $Certificate['YRAPP'] . '</td>
        <td style="text-align:left;">Date of Appointment:</td>
    </tr>
        </table>
        ';

    $pdf->writeHTML($html, true, false, true, false, '');

    // حفظ وعرض الملف
    $pdf->Output('certificate.pdf', 'I');
}
?>

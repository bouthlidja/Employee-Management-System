<?php
header('Content-Type: application/json');
// include '../connect.php';
 include '../connect.php';
// include "../includes/functions/functions.php";
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    
        $sectorId = htmlspecialchars($_POST['sectorId']);
        
echo $sectorId;
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
       
   
// }

?>



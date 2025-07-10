<?php
// error_reporting(0);
$db_host        = 'localhost';
$db_port        = '1521';
$db_server_name = 'Emp_Manag_PDB';
$db_user_name   = 'adminDEV';
$db_user_pass   = 'empMuni';
$conn_string = "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = $db_host)(PORT = $db_port))
    (CONNECT_DATA =
      (SERVER = DEDICATED)
      (SERVICE_NAME = $db_server_name )
    )
  )";
  $conn = oci_connect($db_user_name, $db_user_pass, $conn_string);
  if(!$conn){
    $e = oci_error();
    echo "can not connect your database" . $e['message'];
    
  }
  // else{
  //   echo 'connected ';
  // }



  


?>
<?php
include 'connect.php';

function getTitle(){
    global $pageTitle ;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else {
        echo 'default';
    }
  
}



function test_input($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    stripcslashes($data);
    return $data;
}

function getCount($conn,$table){     
    $sql = "SELECT COUNT(*) AS total_count FROM $table";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt) ;
                
    $row = oci_fetch_assoc($stmt);
    $result['status'] = 'success';
    $result['count'] = $row['TOTAL_COUNT'];    
    return    json_encode($result);
}

function selectAll($conn,$table){
    $result['error']=false;
    $result['message']="";
    $users = array();
    $sql = "SELECT * FROM $table ORDER BY usrID";
    $stmt = oci_parse($conn, $sql);
    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($users, $rows);
        }
        $result['users'] = $users;
       
  }else{
    $e = oci_error();
    
  }
  return json_encode($result);
}

function searshFun($conn,$table, $valID){


    // if (empty($result['obj'])) {
    //     $result['status']='error';
    //     $result['message'] = "No records found for empID = $valID";
    // }
    $obj = array();
    $sql = "SELECT * FROM $table WHERE  empID = :paramEmpID ORDER BY empID"; 
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':paramEmpID', $valID);
    if(oci_execute($stmt)){
        while($rows = oci_fetch_assoc($stmt)){
            array_push($obj, $rows);
        }
        
        $result['obj'] = $obj;
       
  }else{
    $e = oci_error();
    
  }
  return json_encode($result);


}

 





// This function adds a new notification to the notification table in the database.
function addNotification($conn, $usrID, $msg) {
    $sql = "INSERT INTO  T_Notifications (notID, usrID, msg) VALUES (SEQ_NOT.NEXTVAL, :usrID, :msg)";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':usrID', $usrID);
    oci_bind_by_name($stmt, ':msg', $msg);
 
    if (oci_execute($stmt)) {
        oci_commit($conn);
     
    }
}

?>


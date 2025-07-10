<?php
session_start();

$pageTitle = "Login";

include "init.php";






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = test_input($_POST['user']);
    $password = test_input($_POST['pass']);

    // استعلام لاختيار بيانات المستخدم بناءً على اسم المستخدم
    $sql = "SELECT * FROM T_Users WHERE username = :username";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':username', $name);
    oci_execute($stmt);

    $rows = [];
    $num_rows = oci_fetch_all($stmt, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    if ($num_rows > 0) {
        // جلب كلمة المرور المشفرة من قاعدة البيانات
        $hashedPassword = $rows[0]["PSSWRD"];

        // التحقق من كلمة المرور باستخدام password_verify
        if (password_verify($password, $hashedPassword)) {
            // إذا كانت كلمة المرور صحيحة
            $_SESSION['username'] = $name;
            $_SESSION['id'] = $rows[0]["USRID"]; // تعديل الصفحة
            $_SESSION['emp'] = $rows[0]["EMPID"]; // صفحة الطلب
            $_SESSION['role'] = $rows[0]["USRROLE"]; // حفظ الصلاحية في الجلسة

            // ✅ تسجيل النشاط في log_active
            $ip = $_SERVER['REMOTE_ADDR']; // الحصول على IP المستخدم
            $userID = $_SESSION['id'];
            $actionType = "User Login";

            $logSQL = "INSERT INTO T_log_active (logID, usrID, action_type, action_time, ip_address) 
                       VALUES (SEQ_LOG.NEXTVAL, :usrID, :actionType, SYSTIMESTAMP, :ipAddress)";
            $stmtLog = oci_parse($conn, $logSQL);

            oci_bind_by_name($stmtLog, ':usrID', $userID);
            oci_bind_by_name($stmtLog, ':actionType', $actionType);
            oci_bind_by_name($stmtLog, ':ipAddress', $ip);

            
            if(oci_execute($stmtLog)){
                oci_commit($conn);
            }
           
           

            if ($_SESSION['role'] === 'admin') {
                header('location: Dashboard_page.php');
            } elseif ($_SESSION['role'] === 'user') {
                header('location: requests_page.php');
            } else {
                header('location: unauthorized.php');
            }
       
            
            exit();
        } else {
            // كلمة المرور غير صحيحة
            $msg = "Invalid password.";
        }
    } else {
        // اسم المستخدم غير موجود
        $msg = "Username does not exist.";
    }
}
?>



<div id="login">

    <div class="contentForm" >
        <form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method="POST" class="Form"  autocomplete="off">
        <h4 class="text-center">LOGIN</h4>
        <span> <?php  if(isset($msg)){echo $msg;}  ?></span>
        <input class="form-control" type="text" name="user" placeholder="user name" autocomplate= "off">
        <input class="form-control psswrd" type="password" name="pass" placeholder="password" autocomplate= "new-password">
        <div class="showPassword">
        <input type="checkbox" id="showPassword"> <label for="showPassword">show password</label>
        </div>
        
        <input class="btn btn-primary btn-block"   type="submit" name="login" value="login">
        </form>
        <a href="change_password_page.php">Forgot password?</a>
    </div>
</div>





<?php

    include $tpl . "footer.php";


?>
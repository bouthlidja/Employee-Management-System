<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include '../init.php';



require  $lib  . 'PHPMailer/src/Exception.php';
require $lib  . 'PHPMailer/src/PHPMailer.php';
require $lib  . 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
     
    $emailUser = $_POST['email'] ?? '';
    $randomNumber = random_int(100000, 999999);

    $_SESSION['verification_code'] = $randomNumber; // حفظ الرمز في الجلسة
    $_SESSION['email_sent'] = true;

    // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
    $sql = "SELECT usrEml FROM T_Users WHERE usrEml = :paramUsrEml";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':paramUsrEml', $emailUser);
    oci_execute($stmt);

    $rows = [];
    $num_rows = oci_fetch_all($stmt, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    if ($num_rows > 0) {
            $mail = new PHPMailer(true);
            try {
                // إعدادات SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alibouthlidja25@gmail.com'; // بريدك الإلكتروني
                $mail->Password = 'xirn udam gtvy vohk'; // كلمة مرور التطبيق
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587; // استخدم 465 إذا كنت تستخدم ENCRYPTION_SMTPS

                // إعداد البريد
                $mail->setFrom('alibouthlidja25@gmail.com', 'EMS');
                $mail->addAddress($emailUser);
                $mail->isHTML(true);
                $mail->Subject = 'Your verification code';
                $mail->Body = "Your verification code is : <b>$randomNumber</b>";

                $mail->send();
                
                if (isset($_SESSION['email_sent'])){?>

        <link rel="stylesheet" href="../layout/css/change_password_style.css">

        <div class="sendCode">
            <h2>Enter the code sent</h2>
            <form action="verify_code.php" method="POST">

                <div class="form-group " style="display: none;"> 
                    <label> email </label>
                    <input type="text" name="email" class="form-control" value="<?php if(isset($emailUser)){echo $emailUser;}  ?>"  >
                </div>

                <div class="form-group"> 
                    <label> Enter the code sent </label>
                    <input type="text" name="code" class="form-control inputCode" > 
                </div>
                <button type="submit" class="btn btn-primary ">verification</button>

            
            </form>
        </div>





        <?php
                }   
                    

            } catch (Exception $e) {
                echo "Transmission error: {$mail->ErrorInfo}";
            }
    } else {
        $result["num"] = $num_rows;
        $result["status"] = "error";
        $result["message"] = "not good.";
        echo json_encode($result);
    }

}
 
?>
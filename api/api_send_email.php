<?php
session_start();
include '../connect.php';
header('Content-Type: application/json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../includes/libraris/PHPMailer/src/Exception.php';
require   '../includes/libraris/PHPMailer/src/PHPMailer.php';
require   '../includes/libraris/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

$emailEmp = $_POST['email'] ?? '';
$dept = $_POST['dept'] ?? '';
$subject = $_POST['subject'] ?? 'No Subject';
$message = $_POST['message'] ?? 'No Message';

$result = ["status" => "error", "message" => "Unknown error"];

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'exmple@gmail.com';
    $mail->Password = 'xirn 000 rezc kml';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('alibouthlidja25@gmail.com', 'EMS');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if (!empty($_FILES['attachment']['name'])) {
        $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
    }

    if (!empty($emailEmp)) {
        $mail->addAddress($emailEmp);
        $mail->send();
        $result = ["status" => "success", "message" => "Email sent successfully to $emailEmp"];
    } elseif (!empty($dept)) {
        $sql = "SELECT emlEmp FROM T_employees WHERE DepID = :dept";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':dept', $dept);
        oci_execute($stmt);

        $emailsSent = 0;
        while ($row = oci_fetch_assoc($stmt)) {
            $mail->clearAddresses();
            $mail->addAddress($row['EMLEMP']);
            $mail->send();
            $emailsSent++;
        }
        $result = ["status" => "success", "message" => "Emails sent successfully to $emailsSent employees in department $dept"];
    } else {
        $result = ["status" => "error", "message" => "Please enter an email or select a department."];
    }
} catch (Exception $e) {
    $result = ["status" => "error", "message" => "Email sending failed: {$mail->ErrorInfo}"];
}

echo json_encode($result);
exit;
?>

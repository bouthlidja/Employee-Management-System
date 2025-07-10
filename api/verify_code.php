<?php
session_start();
// include '../init.php';
include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['code']) && isset($_POST['email'])) {
        $email = $_POST['email'] ?? '';
        $userCode = $_POST['code'];
        $correctCode = $_SESSION['verification_code'] ?? null;

        if ($userCode == $correctCode) {
            unset($_SESSION['verification_code']); // حذف الرمز بعد التحقق
            ?>
            <!DOCTYPE html>
            <html lang="ar">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Create a New Password</title>
                <link rel="stylesheet" href="../layout/css/change_password_style.css">
            </head>
            <body>
                <div class="verifyCode">
                    <div class="form-container">
                        <h1 class="form-title">Create New Password</h1>
                        <form method="POST" action="">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            <div class="mb-3 password">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control form-input" id="exampleInputPassword1" name="new_password" required>
                            </div>
                            <div class="mb-3 password">
                                <label for="exampleInputPassword2" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control form-input" id="exampleInputPassword2" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary form-button">Submit</button>
                        </form>
                    </div>
                </div>      
            </body>
            </html>
            <?php
        } else {
            echo "<h2>Incorrect code, please try again.</h2>";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['confirm_password']) && isset($_POST['email'])) {
        $email = $_POST['email'] ?? '';
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // تشفير كلمة المرور
            $SQL = "UPDATE T_Users SET psswrd = :paramUsrPsswrd WHERE usrEml = :paramUsrEml";
            $stmt = oci_parse($conn, $SQL);
            oci_bind_by_name($stmt, ':paramUsrPsswrd', $hashedPassword);
            oci_bind_by_name($stmt, ':paramUsrEml', $email);

            if (oci_execute($stmt)) {
                oci_commit($conn);
                echo "<h2>Password updated successfully.</h2>";
            } else {
                echo "<h2>An error occurred while updating the password.</h2>";
            }
        } else {
            echo "<h2>Password and confirmation do not match.</h2>";
        }
    }
}
?>

<?php
session_start();
require '../config/connection.php';
require '../config/function.php'; // Koneksi ke function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = processForgotPassword($_POST); // Function untuk menangani lupa password
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postcard Forgot Password</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/loginregis.css">
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/pleart.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>Lupa Password</h2>
            <form action="forgot_password.php" method="POST">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    <span class="error-message"><?= isset($errors['email']) ? $errors['email'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password">
                    <span
                        class="error-message"><?= isset($errors['new_password']) ? $errors['new_password'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                    <span
                        class="error-message"><?= isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></span>
                </div>

                <button type="submit">Reset Password</button>
            </form>
            <div class="footer">
                <p><a href="login.php">Kembali ke Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>
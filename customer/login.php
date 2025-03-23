<?php
session_start();
require '../config/connection.php';
require '../config/function.php'; // Koneksi ke function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = loginCustomer($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postcard Register Form</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/loginregis.css">
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/pleart.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>Login</h2>
            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    <span class="error-message"><?= isset($errors['email']) ? $errors['email'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <span class="error-message"><?= isset($errors['password']) ? $errors['password'] : ''; ?></span>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="footer">
                <p>Belum punya akun? <a href="register.php">Registrasi</a></p>
                <p><a href="../customer/forgot_password.php">Lupa Password?</a></p>
            </div>
        </div>
    </div>
</body>

</html>
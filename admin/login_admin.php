<?php
session_start();
require '../config/connection.php'; // Pastikan jalur ini sesuai dengan struktur folder Anda
require '../config/function.php'; //

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responLoginAdmin = loginAdmin($_POST);
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PleeART</title>
    <link rel="stylesheet" href="../resources/css/loginregis.css"> <!-- Pastikan jalur CSS benar -->
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/imglogin.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>LOGIN ADMIN</h2>

            <!-- Tampilkan pesan error jika ada -->
            <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></p>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="login_admin.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email">
                    <span
                        class="error-message"><?= isset($responLoginAdmin['email']) ? $responLoginAdmin['email'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <span
                        class="error-message"><?= isset($responLoginAdmin['login']) ? $responLoginAdmin['login'] : ''; ?></span>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
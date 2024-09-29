<?php
session_start();
require '../config/connection.php'; // Koneksi ke database
require '../config/function.php'; // Koneksi ke function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $responRegist = registrasiAdmin($_POST);

    if (isset($responRegist['status']) && $responRegist['status'] === 'success') {
        // $success_message = $registerResponse['message'];
        header("Location: login_admin.php");
        exit();
    } else {
        // If errors exist, handle them
        $errors = $responRegist;
    }

}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - PleeART</title>
    <link rel="stylesheet" href="../resources/css/loginregis.css"> <!-- Pastikan jalur CSS benar -->
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/imglogin.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>Register Admin</h2>

            <!-- Tampilkan pesan error jika ada -->
            <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="ini_register_admin_loh.php" method="POST">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    <span class="error-message"><?= isset($responRegist['name']) ? $responRegist['name'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    <span
                        class="error-message"><?= isset($responRegist['email']) ? $responRegist['email'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <span
                        class="error-message"><?= isset($responRegist['password']) ? $responRegist['password'] : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for=" confirm-password">Ulangi Password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                    <span
                        class="error-message"><?= isset($responRegist['confirm_password']) ? $responRegist['confirm_password'] : ''; ?></span>
                </div>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>

</html>
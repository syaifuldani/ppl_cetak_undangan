<?php
session_start();
require '../config/connection.php'; // Koneksi ke database
require '../config/function.php'; // Koneksi ke function

// 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Attempt to register the user and capture any errors
    $registerResponse = registerCustomer($_POST);

    // var_dump($registerResponse);

    if (isset($registerResponse['status']) && $registerResponse['status'] === 'success') {
        $success_message = $registerResponse['message'];
    } else {
        // If errors exist, handle them
        $errors = $registerResponse;
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postcard Register Form</title>
    <link rel="stylesheet" href="../resources/css/loginregis.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/imglogin.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    <span
                        class="error-message"><?= isset($registerResponse['name']) ? $registerResponse['name'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    <span
                        class="error-message"><?= isset($registerResponse['email']) ? $registerResponse['email'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <span
                        class="error-message"><?= isset($registerResponse['password']) ? $registerResponse['password'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Repeat Password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                    <span
                        class="error-message"><?= isset($registerResponse['confirm_password']) ? $registerResponse['confirm_password'] : ''; ?></span>
                </div>

                <button type="submit">Register</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login sini</a></p>
        </div>

    </div>
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $success_message ?>'
    }).then(function() {
        window.location = "login.php"; // Redirect after user confirms the alert
    });
    </script>
</body>

</html>
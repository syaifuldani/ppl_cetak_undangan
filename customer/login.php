<?php
session_start();
require '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk memeriksa user di database
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika user ditemukan dan password cocok
    if ($user && password_verify($password, $user['password'])) {
        // Buat session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: index.php"); // Redirect ke halaman dashboard
        exit();
    } else {
        echo "Email atau password salah!";
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
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="../resources/img/icons/imglogin.png" alt="Postcard">
        </div>
        <div class="form-section">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Register sini</a></p>
        </div>
    </div>
</body>

</html>
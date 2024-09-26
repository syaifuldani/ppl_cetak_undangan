<?php
session_start();
require '../config/connection.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok!";
        exit();
    }

    // Cek apakah email sudah ada di database
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Email sudah terdaftar!";
        exit();
    }

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data pengguna baru ke database
    $sql = "INSERT INTO users (nama_lengkap, email, password) VALUES (:name,:email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        // Buat session untuk user baru
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        header("Location: dashboard.php"); // Redirect ke dashboard setelah registrasi sukses
        exit();
    } else {
        echo "Terjadi kesalahan saat registrasi!";
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
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <label for="name">Nama Lengkap</label>
                <input type="name" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Repeat Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">Register</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login sini</a></p>
        </div>
    </div>
</body>

</html>
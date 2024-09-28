<?php
session_start();
require '../config/connection.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan bersihkan input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak cocok!";
        header("Location: register_admin.php");
        exit();
    }

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid!";
        header("Location: register_admin.php");
        exit();
    }

    try {
        // Cek apakah email sudah ada di database
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['error'] = "Email sudah terdaftar!";
            header("Location: register_admin.php");
            exit();
        }

        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data pengguna baru ke database dengan jenis_pengguna 'admin'
        $sql = "INSERT INTO users (nama_lengkap, email, password, jenis_pengguna) VALUES (:name, :email, :password, 'admin')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Set pesan sukses dalam session
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: login_admin.php"); // Redirect ke halaman login admin
            exit();
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat registrasi!";
            header("Location: register_admin.php");
            exit();
        }
    } catch (PDOException $e) {
        // Tangani error database
        $_SESSION['error'] = "Terjadi kesalahan pada server: " . $e->getMessage();
        header("Location: register_admin.php");
        exit();
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
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="register_admin.php" method="POST">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Ulangi Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">Register</button>
            </form>
            <p>Sudah punya akun admin? <a href="login_admin.php">Login di sini</a></p>
        </div>
    </div>
</body>

</html>

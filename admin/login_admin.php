<?php
session_start();
require '../config/connection.php'; // Pastikan jalur ini sesuai dengan struktur folder Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari form dan bersihkan input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan password wajib diisi!";
        header("Location: login_admin.php");
        exit();
    }

    try {
        // Query untuk memeriksa user di database dan memastikan jenis_pengguna adalah 'admin'
        $sql = "SELECT * FROM users WHERE email = :email AND jenis_pengguna = 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            // Buat session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['jenis_pengguna'] = $user['jenis_pengguna'];
            header("Location: dashboard.php"); // Redirect ke halaman dashboard admin
            exit();
        } else {
            // Simpan pesan error dalam session
            $_SESSION['error'] = "Email atau password salah!";
            header("Location: login_admin.php"); // Redirect kembali ke halaman login admin
            exit();
        }
    } catch (PDOException $e) {
        // Tangani error database
        $_SESSION['error'] = "Terjadi kesalahan pada server: " . $e->getMessage();
        header("Location: login_admin.php");
        exit();
    }
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

            <!-- Tampilkan pesan sukses jika ada -->
            <?php if (isset($_SESSION['success'])): ?>
                <p style="color: green;"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <!-- Tampilkan pesan error jika ada -->
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="login_admin.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Belum punya akun admin? <a href="register_admin.php">Register di sini</a></p>
        </div>
    </div>
</body>

</html>

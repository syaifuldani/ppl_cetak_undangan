<?php
session_start();
require '../config/connection.php'; // Menghubungkan ke database

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cek apakah data dikirim melalui form
    $name = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $nohp = isset($_POST['nomor_telepon']) ? $_POST['nomor_telepon'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Dapatkan ID user dari sesi
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Jika user_id ada, lakukan update ke database
    if ($user_id > 0) {
        // Query untuk update data user
        $sql = "UPDATE users SET nama_lengkap = :name, alamat = :alamat, nomor_telepon = :nohp, email = :email WHERE user_id = :user_id";
        
        // Persiapkan dan eksekusi query
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':alamat' => $alamat,
            ':nohp' => $nohp,
            ':email' => $email,
            ':user_id' => $user_id
        ]);

        // Jika berhasil, redirect atau tampilkan pesan sukses
        if ($stmt->rowCount()) {
            echo '<div class="message-container success-message">
                    <span class="message-title">Sukses:</span> Perubahan berhasil disimpan!
                  </div>';
        } else {
            echo '<div class="message-container error-message">
                    <span class="message-title">Error:</span> Tidak ada perubahan yang disimpan.
                  </div>';
        }
    } else {
        echo '<div class="message-container error-message">
                 <span class="message-title">Error:</span> User ID tidak ditemukan!
              </div>';
    }
}

// Ambil ID user dari session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id > 0) {
    // Query untuk mengambil data user dari database
    $sql = "SELECT nama_lengkap, alamat, nomor_telepon, email FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    
    // Ambil data user
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Simpan data ke dalam session
        $_SESSION['user_name'] = $user['nama_lengkap'];
        $_SESSION['alamat'] = $user['alamat'];
        $_SESSION['nomor_telepon'] = $user['nomor_telepon'];
        $_SESSION['user_email'] = $user['email'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Customer</title>
    <link rel="stylesheet" href="../resources/css/profilecustomer.css">
</head>
<body>
    <a href="index.php" class="back-button">Kembali</a>
    <div class="container">
        <h1>Profil Anda</h1>
        <div class="content-wrapper">
            <div class="profile-info">
                <div class="profile-pic">
                    <img src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                        alt="Profile Picture">
                </div>
                <h2 class="profile-name"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Nama Tidak Ditemukan' ?></h2>
            </div>

            <div class="form-container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap" value="<?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Alamat Lengkap" value="<?= isset($_SESSION['alamat']) ? $_SESSION['alamat'] : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="nomor_telepon">No Handphone</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" placeholder="No Hp Anda" value="<?= isset($_SESSION['nomor_telepon']) ? $_SESSION['nomor_telepon'] : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="Email" value="<?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '' ?>">
                    </div>

                    <button class="btn" type="submit">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

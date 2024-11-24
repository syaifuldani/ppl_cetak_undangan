<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Jika belum login, redirect ke halaman login
    exit();
}
require '../config/connection.php'; // Menghubungkan ke database
require '../config/function.php'; //

// Variabel untuk status pesan
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = updateProfileUser($_POST);

    // Jika berhasil, redirect atau tampilkan pesan sukses
    if ($update['status'] === true) {
        $success_message = $update['message'];
    } else {
        $error_message = $update['message'];
    }
}

// Live Search
if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    searchProducts($searchTerm);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile, <?= $_SESSION['user_name'] ?></title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/profilecustomer.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>
        <!-- Menampilkan hasil pencarian -->
        <div id="navbarSearchResults" class="search-results">
            <!-- Hasil pencarian akan ditampilkan di sini -->
        </div>

        <div class="content">
            <div class="content-wrapper">
                <div class="form-container">
                    <form action="" method="post" enctype="multipart/form-data">
                        <h1>Profil Anda</h1>
                        <div class="profile-info">
                            <div class="profile-pic">
                                <img id="profileImage"
                                    src="<?= isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : '../resources/img/profiledefault.png' ?>"
                                    alt="Profile Picture">
                                <span class="edit-text">Edit your photo</span>
                                <input type="file" id="imageUpload" name="profile-image" accept="image/*"
                                    style="display: none;">
                            </div>
                            <h2 class="profile-name"><?= $_SESSION['user_name'] ?></h2>
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap"
                                value="<?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" placeholder="Alamat Lengkap"
                                value="<?= isset($_SESSION['alamat']) ? $_SESSION['alamat'] : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="nomor_telepon">No Handphone</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" placeholder="No Hp Anda"
                                value="<?= isset($_SESSION['nomor_telepon']) ? $_SESSION['nomor_telepon'] : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" placeholder="Email"
                                value="<?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '' ?>">
                        </div>

                        <button class="btn" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

    <!-- Cek apakah ada pesan sukse s atau error -->
    <script>
        <?php if ($success_message): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $success_message ?>'
            });
        <?php elseif ($error_message): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $error_message ?>'
            });
        <?php endif; ?>
    </script>
    <script>
        document.querySelector('.profile-pic').addEventListener('click', function() {
            document.querySelector('#imageUpload').click();
        });

        document.querySelector('#imageUpload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('#profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        document.querySelector('.profile-pic').addEventListener('click', function() {
            document.querySelector('#imageUpload').click();
        });

        document.querySelector('#imageUpload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('#profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
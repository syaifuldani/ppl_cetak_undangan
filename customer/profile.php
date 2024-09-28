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
    <title>Welcome, Amanda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .content-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .profile-info {
            flex: 1;
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            flex-direction: column;
            justify-content: center;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .profile-email {
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .form-container {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .add-email-btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .add-email-btn:hover {
            background-color: #007bff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
        }

        .profile-pic-header {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-pic-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media screen and (max-width:800px) {
            .content-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-button">Kembali</a>
    <div class="container">
        <h1>Profil Anda</h1>
        <div class="content-wrapper">
            <div class="profile-info">
                <div class="profile-pic">
                    <img id="profileImage"
                        src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                        alt="Profile Picture">
                    <span class="edit-text">Edit your photo</span>
                    <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                </div>
                <h2 class="profile-name">Alexa Rawles</h2>
            </div>

            <div class="form-container">
                <form action="" method="post">
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

    <script>
        const editButton = document.getElementById('editButton');
        const addEmailButton = document.getElementById('addEmailButton');

        editButton.addEventListener('click', () => {
            // Implement logic to enable editing profile details
            alert('Edit button clicked. Implement editing logic here.');
        });

        addEmailButton.addEventListener('click', () => {
            // Implement logic to add a new email address
            alert('Add Email Address button clicked. Implement adding logic here.');
        });
    </script>
</body>

</html>
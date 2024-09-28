<?php
session_start();

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
            position: relative;
            cursor: pointer;
            display: inline-block;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* opacity: 0.7; */
        }

        /* Css setelah dihover */

        .edit-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            padding: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 0 0 50% 50%;
            font-size: 14px;
        }

        .profile-pic:hover img {
            opacity: 0.7;
        }

        .profile-pic:hover .edit-text {
            opacity: 1;
        }

        /* End */

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
    <a href="index.php">
        <h3>Kembali</h3>
    </a>
    <div class="container">
        <h1>Profil anda</h1>
        <div class="content-wrapper">
            <div class="profile-info">
                <div class="profile-pic">
                    <img id="profileImage"
                        src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                        alt="Profile Picture">
                    <span class="edit-text">Edit your photo</span>
                    <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                </div>
                <h2 class="profile-name"><?= $_SESSION['user_name'] ?></h2>
            </div>

            <div class="form-container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" placeholder="Nama lengkap" value="<?= $_SESSION['user_name'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" placeholder="Alamat Lengkap"
                            value="<?= $_SESSION['user_name'] ? "-" : "-" ?>">
                    </div>

                    <div class="form-group">
                        <label for="nohp">No Handphone</label>
                        <input type="text" id="nohp" placeholder="No Hp Anda"
                            value="<?= $_SESSION['user_name'] ? "-" : "-" ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" placeholder="No Hp Anda" value="<?= $_SESSION['user_email'] ?>">
                    </div>

                    <button class="btn" type="submit" id="addEmailButton">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.profile-pic').addEventListener('click', function () {
            document.querySelector('#imageUpload').click();
        });

        document.querySelector('#imageUpload').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('#profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
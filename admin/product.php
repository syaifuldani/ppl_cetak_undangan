<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

$jenishalaman = "Produk";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <main class="main-content">

            <!-- Header Fix -->
            <?php require "template/header.php"; ?>

            <!-- Button untuk menambah barang -->
            <div class="add-product-button">
                <button onclick="window.location.href='tambah_barang.php'">Tambah Barang</button>
            </div>

            <div class="content">
                <h3>List Produk</h3>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><img src="../admin/style/img/tahlil.jpeg" alt="Gambar Produk"></td>
                                <td>#25426</td>
                                <td>Undangan Pernikahan</td>
                                <td>₹200.00</td>
                                <td>
                                    <div class="aksi">
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="../admin/style/img/tahlil.jpeg" alt="Gambar Produk"></td>
                                <td>#25426</td>
                                <td>Undangan Pernikahan</td>
                                <td>₹200.00</td>
                                <td>
                                    <div class="aksi">
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="../admin/style/img/tahlil.jpeg" alt="Gambar Produk"></td>
                                <td>#25426</td>
                                <td>Undangan Pernikahan</td>
                                <td>₹200.00</td>
                                <td>
                                    <div class="aksi">
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="../admin/style/img/tahlil.jpeg" alt="Gambar Produk"></td>
                                <td>#25426</td>
                                <td>Undangan Pernikahan</td>
                                <td>₹200.00</td>
                                <td>
                                    <div class="aksi">
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="../admin/style/img/tahlil.jpeg" alt="Gambar Produk"></td>
                                <td>#25426</td>
                                <td>Undangan Pernikahan</td>
                                <td>₹200.00</td>
                                <td>
                                    <div class="aksi">
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <ul>
                        <li class="active">1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>...</li>
                        <li>10</li>
                        <li><a href="#">NEXT ></a></li>
                    </ul>
                </div>
            </div>

        </main>
    </div>
</body>

</html>
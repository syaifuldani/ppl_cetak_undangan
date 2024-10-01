<?php
session_start();
require '../config/connection.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

// Data dari session setelah login
$title = "PleeART";
$jenishalaman = "Dashboard";
$user_email = $_SESSION['user_email']; // Email user yang diambil dari session

// Ambil data produk undangan tahlil dari database
$kategori = "Undangan Ulang Tahun"; // Kategori yang ingin ditampilkan
$sql = "SELECT product_id, nama_produk, deskripsi, harga_product, gambar_satu FROM products WHERE kategori = :kategori";
$stmt = $GLOBALS["db"]->prepare($sql);
$stmt->execute(['kategori' => $kategori]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <main class="main-content">

            <?php require "template/header.php"; ?>

            <section class="product-list">
                <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="data:image/jpeg;base64,<?= base64_encode($product['gambar_satu']); ?>" alt="<?= htmlspecialchars($product['nama_produk']); ?>" style="width: 300px; height: auto;">
                    <div class="product-details">
                        <h3><?= htmlspecialchars($product['nama_produk']); ?></h3>
                        <p><?= htmlspecialchars($product['deskripsi']); ?></p>
                        <p>Rp. <?= htmlspecialchars(number_format($product['harga_product'], 2, ',', '.')); ?></p>
                        <div class="stats">
                            <span>Terjual: <?= htmlspecialchars($product['terjual'] ?? '0'); ?></span>
                            <span>Stok: <?= htmlspecialchars($product['stok'] ?? '0'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>

            <div class="pagination">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <span>...</span>
                <a href="#">10</a>
                <a href="#">Next</a>
            </div>
        </main>
    </div>
</body>

</html>
<?php
session_start();
require '../config/connection.php';

// Ambil data produk undangan pernikahan dari database
$kategori = "Undangan Tahlil & Kirim Doa"; // Kategori yang ingin ditampilkan
$sql = "SELECT product_id, nama_produk, deskripsi, harga_produk, gambar_satu FROM products WHERE kategori = :kategori";
$stmt = $GLOBALS["db"]->prepare($sql);
$stmt->execute(['kategori' => $kategori]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Tahlil & Kirim Doa</title>
    <link rel="stylesheet" href="../resources/css/dashboard.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <!-- Items Product -->
        <div class="product-container">
            <div class="product-content">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img class="product" src="data:image/jpeg;base64,<?= base64_encode($product['gambar_satu']); ?>"
                        alt="<?= htmlspecialchars($product['nama_produk']); ?>">
                    <p class="product-name"><?= htmlspecialchars($product['nama_produk']); ?></p>
                    <div class="description">
                        <h4>Deskripsi Produk</h4>
                        <p><?= htmlspecialchars($product['deskripsi']); ?></p>
                    </div>
                    <p class="product-price">Rp.
                        <?= htmlspecialchars(number_format($product['harga_product'], 2, ',', '.')); ?>
                    </p>
                    <a href="productdetail.php?id=<?= $product['product_id']; ?>" class="detail-button"><img
                            class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                        <p>Lihat Detail</p>
                    </a>
                </div>
                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                <p>Produk tidak ditemukan untuk kategori ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <script src="../resources/js/slides.js"></script>
        <script src="../resources/js/burgersidebar.js"></script>
    </div>
</body>

</html>
<?php
session_start();
require '../config/connection.php';
require '../config/function.php'; 

// Ambil data produk undangan pernikahan dari function
$products = getProductData('Walimatul');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Walimatul</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <title>Produk Khitan</title>
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

                <!-- Jika ada error dalam mengambil produk -->
                <?php if (isset($products['error'])): ?>
                    <p>Error: <?= htmlspecialchars($products['error']); ?></p>
                <?php elseif (empty($products)): ?>
                    <p>Produk tidak ditemukan untuk kategori ini.</p>
                <?php else: ?>
                <!-- Loop produk jika ditemukan -->
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img class="product" src="<?= $product['gambar_satu']; ?>"
                                alt="<?= htmlspecialchars($product['nama_produk']); ?>">
                            <p class="product-name"><?= htmlspecialchars($product['nama_produk']); ?></p>
                            <div class="description">
                                <h5>Deskripsi Produk</h5>
                                <p><?= htmlspecialchars($product['deskripsi']); ?></p>
                            </div>
                            <p class="product-price">Rp.
                                <?= htmlspecialchars(number_format($product['harga_produk'], 2, ',', '.')); ?></p>
                            <a href="productdetail.php?id=<?= $product['product_id']; ?>" class="detail-button">
                                <img class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                                <p>Lihat Detail</p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <script src="../resources/js/burgersidebar.js"></script>
    </div>
</body>

</html>

<?php
session_start();
require '../config/connection.php';
require '../config/function.php'; 

// Ambil data produk undangan pernikahan dari function
$products = getProductData('Pernikahan');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan</title>
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
                <?php if (isset($products['error'])): ?>
                    <p>Error: <?= htmlspecialchars($products['error']); ?></p>
                <?php else: ?>
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
                                <?= htmlspecialchars(number_format($product['harga_produk'], 2, ',', '.')); ?>
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
                <?php endif; ?>
            </div>
        </div>

        <script src="../resources/js/slides.js"></script>
        <script src="../resources/js/burgersidebar.js"></script>
    </div>
</body>

</html>

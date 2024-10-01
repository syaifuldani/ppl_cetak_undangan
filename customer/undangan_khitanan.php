<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Khitanan</title>
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
        <?php
        // Include the function file
        include_once '../config/function.php';

        // Call the function to get the products
        $products = getPernikahanProducts();

        // Check if there are products to display
        if (isset($products['error'])) {
            echo "<p>Error: " . $products['error'] . "</p>";
        } else {
            foreach ($products as $product) {
                echo '
        <div class="product-container">
            <div class="product-content">
                <div class="product-card">
                    <img class="product" src="' . $product['gambar_satu'] . '" alt="' . $product['nama_produk'] . '">
                    <p class="product-name">' . $product['nama_produk'] . '</p>
                    <div class="description">
                        <h6>Deskripsi Produk</h6>
                        <p>' . $product['deskripsi'] . '</p>
                    </div>
                    <p class="product-price">Rp. ' . number_format($product['harga_produk'], 2, ',', '.') . '</p>
                    <a href="productdetail.php?product_id=' . $product['id'] . '" class="detail-button">
                        <img class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                        <p>Lihat Detail</p>
                    </a>
                </div>
            </div>
        </div>';
            }
        }

        ?>
    </div>

        <script src="../resources/js/slides.js"></script>
        <script src="../resources/js/burgersidebar.js"></script>
</body>

</html>
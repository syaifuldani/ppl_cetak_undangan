<?php
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

            <section class="product-list">
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <div class="product-main">
                            <h3>Undangan Pernikahan</h3>
                            <p>Rp. 1.300</p>
                        </div>
                        <!-- <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p> -->
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
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
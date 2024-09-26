<?php
session_start();

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
?>

<?php
$title = "Undangan Pernikahan";
$jenishalaman = "Undangan Pernikahan";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <main class="main-content">

            <?php require "template/header.php"; ?>

            <section class="product-list">
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="./style/img/undangan1.jpg" alt="Undangan Pernikahan">
                    <div class="product-details">
                        <h3>Lorem Ipsum</h3>
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 10.000</p>
                        <p>Summary: Lorem ipsum is placeholder text commonly used in the graphic.</p>
                        <div class="stats">
                            <span>Terjual: 1269</span>
                            <span>Stok: 1269</span>
                        </div>
                    </div>
                </div>
                <!-- Ulangi div ini untuk produk lainnya -->
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
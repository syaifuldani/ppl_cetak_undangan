<?php
session_start();
require '../config/function.php';

// Live Search
if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    searchProducts($searchTerm);
}

// Fungsi untuk mengecek apakah user sudah login
function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : null;
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;

    // Validasi input
    if ($product_id && $user_id && is_numeric($quantity) && is_numeric($price)) {
        // Hitung total harga
        $total_price = $quantity * $price;

        // Panggil fungsi untuk menyimpan ke database
        if (addToCart($product_id, $user_id, $quantity, $total_price)) {
            // Simpan pesan sukses ke dalam session
            $_SESSION['cart_status'] = 'success';
            $_SESSION['cart_message'] = 'Produk berhasil ditambahkan ke keranjang.';

            // Redirect ke halaman productdetail.php dengan ID produk
            header("Location: productdetail.php?id=$product_id");
            exit();
        } else {
            // Jika gagal, simpan pesan error ke dalam session
            $_SESSION['cart_status'] = 'error';
            $_SESSION['cart_message'] = 'Gagal menambahkan produk ke keranjang.';

            // Redirect ke halaman productdetail.php dengan ID produk
            header("Location: productdetail.php?id=$product_id");
            exit();
        }
    } else {
        // Jika data tidak valid
        $_SESSION['cart_status'] = 'error';
        $_SESSION['cart_message'] = 'Data produk tidak valid.';

        // Redirect ke halaman productdetail.php dengan ID produk
        header("Location: productdetail.php?id=$product_id");
        exit();
    }
}

// Ambil detail produk berdasarkan ID
$product_id = isset($_GET['id']) ? $_GET['id'] : null;
if (is_null($product_id)) {
    echo "Error: Produk tidak ditemukan.";
    exit;
}

$product = getProductDetails($product_id);
$products = getRandomProducts(2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/productdetail.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>
        <!-- Menampilkan hasil pencarian -->
        <div id="navbarSearchResults" class="search-results">
            <!-- Hasil pencarian akan ditampilkan di sini -->
        </div>

        <div class="content">
            <div class="content-detail">
                <div class="product-detail">
                    <!-- Section Gambar -->
                    <div class="image-gallery">
                        <div class="image-zoom">
                            <div class="main-image" onmousemove="zoomImage(event)" onmouseleave="resetImage()">
                                <img id="mainImage" src="<?= $product['gambar_satu']; ?>" alt="<?= htmlspecialchars($product['nama_produk']); ?>">
                            </div>
                        </div>
                        <div class="thumbnail-images">
                            <img src="<?= $product['gambar_satu']; ?>" alt="Thumbnail 1" class="thumb" onclick="changeImage(this)">
                            <?php if (isset($product['gambar_dua'])): ?>
                                <img src="<?= $product['gambar_dua']; ?>" alt="Thumbnail 2" class="thumb" onclick="changeImage(this)">
                            <?php endif; ?>
                            <?php if (isset($product['gambar_tiga'])): ?>
                                <img src="<?= $product['gambar_tiga']; ?>" alt="Thumbnail 3" class="thumb" onclick="changeImage(this)">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Section Informasi Produk -->
                    <div class="product-info">
                        <?php if ($product): ?>
                            <!-- Nama produk -->
                            <h1><?= htmlspecialchars($product['nama_produk']); ?></h1>

                            <!-- Harga produk -->
                            <p class="price">Rp. <?= htmlspecialchars(number_format($product['harga_produk'], 2, ',', '.')); ?>/Lembar</p>

                            <div class="description">
                                <h4>Deskripsi Produk</h4>
                                <p><?= htmlspecialchars($product['deskripsi']); ?></p>
                            </div>
                        <?php else: ?>
                            <h1>Produk tidak ditemukan.</h1>
                        <?php endif; ?>

                        <!-- Inputan Kuantitas -->
                        <div class="quantity">
                            <form action="" method="POST" id="addToCartForm">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($product['harga_produk']); ?>">

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">

                                    <button type="button" onclick="decreaseQuantity()">-</button>
                                    <input type="text" name="quantity" id="quantityInput" value="1">
                                    <button type="button" onclick="increaseQuantity()">+</button>

                                    <button type="submit" class="order-btn">
                                        <img src="../resources/img/icons/cart.png" class="cart-icon" alt="">
                                        Pesan Sekarang
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="order-btn">
                                        <img src="../resources/img/icons/cart.png" class="cart-icon" alt="">
                                        Login untuk Pesan
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>

                        <h2>Undangan Lainnya</h2>
                        <div class="product-container">
                            <?php
                            if (!empty($products) && !isset($products['error'])):
                                foreach ($products as $product):
                            ?>
                                    <div class="product-card">
                                        <img class="product" src="<?= htmlspecialchars($product['gambar_satu']); ?>" alt="<?= htmlspecialchars($product['nama_produk']); ?>">
                                        <p class="product-name"><?= htmlspecialchars($product['nama_produk']); ?></p>
                                        <div class="description">
                                            <h6>Deskripsi Produk</h6>
                                            <p><?= htmlspecialchars($product['deskripsi']); ?></p>
                                        </div>
                                        <p class="product-price">Rp. <?= htmlspecialchars(number_format($product['harga_produk'], 2, ',', '.')); ?></p>
                                        <a href="productdetail.php?id=<?= htmlspecialchars($product['product_id']); ?>" class="detail-button">
                                            <img class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                                            <p>Lihat Detail</p>
                                        </a>
                                    </div>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <div class="product-card">
                                    <p>Tidak ada produk tersedia.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <div class="reviews-product">
                <div class="header">
                    <h3>Ulasan Produk</h3>
                    <hr color="black">
                </div>
                <div class="reviews">
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <div class="checkmark-container">
                    <div class="checkmark-circle">
                        <div class="checkmark">✓</div>
                    </div>
                </div>
                <p id="overlayMessage"></p>
                <a href="javascript:hideOverlay()" class="btn-lanjut">Lanjut Belanja</a>
            </div>
        </div>
    </div>

    <script src="../resources/js/thumnail.js"></script>
    <script src="../resources/js/zoomimage.js"></script>
    <script src="../resources/js/overlay.js"></script>
    <script>
        // Cek apakah ada session message dari PHP
        <?php if (isset($_SESSION['cart_status'])): ?>
            const cartStatus = '<?= $_SESSION['cart_status']; ?>';
            const cartMessage = '<?= $_SESSION['cart_message']; ?>';

            if (cartStatus === 'success') {
                showOverlay(cartMessage); // Tampilkan overlay jika berhasil
            } else if (cartStatus === 'error') {
                alert(cartMessage); // Tampilkan pesan error
            }

            // Hapus pesan session setelah ditampilkan
            <?php
            unset($_SESSION['cart_status']);
            unset($_SESSION['cart_message']);
            ?>
        <?php endif; ?>
    </script>
    <script src="../resources/js/burgersidebar.js"></script>
</body>

</html>
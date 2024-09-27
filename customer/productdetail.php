<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan</title>
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/productdetail.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <div class="content">
            <div class="content-detail">
                <div class="product-detail">
                    <!-- Section Gambar -->
                    <div class="image-gallery">
                        <div class="image-zoom">
                            <div class="main-image" onmousemove="zoomImage(event)" onmouseleave="resetImage()">
                                <img id="mainImage" src="../resources/img/icons/Rectangle.png" alt="Undangan Utama">
                            </div>
                        </div>
                        <div class="thumbnail-images">
                            <img src="../resources/img/icons/Rectangle.png" alt="Thumbnail 1" class="thumb" onclick="changeImage(this)">
                            <img src="../resources/img/icons/contohproduct.jpeg" alt="Thumbnail 2" class="thumb" onclick="changeImage(this)">
                            <img src="../resources/img/icons/contohproduct.jpeg" alt="Thumbnail 3" class="thumb" onclick="changeImage(this)">
                        </div>
                    </div>

                    <!-- Section Informasi Produk -->
                    <div class="product-info">
                        <h1>Undangan Blangko Pernikahan</h1>
                        <p class="price">Rp. 1000,00/Lembar</p>
                        <div class="description">
                            <h4>Deskripsi Produk</h4>
                            <p>
                                kontidsbfdyjfvjdrvfrjbdjfraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                            </p>
                        </div>

                        <!-- Inputan Kuantitas -->
                        <div class="quantity">
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="text" id="quantityInput" value="1">
                            <button onclick="increaseQuantity()">+</button>

                            <!-- Tombol Pesan -->
                            <a href="#" class="order-btn" onclick="showOverlay()">
                                <img src="../resources/img/icons/cart.png" class="cart-icon" alt="">
                                Pesan Sekarang
                            </a>
                        </div>

                        <!-- Overlay -->
                        <div id="overlay" class="overlay">
                            <div class="overlay-content">
                                <div class="checkmark-container">
                                    <div class="checkmark-circle">
                                        <div class="checkmark">✓</div>
                                    </div>
                                </div>
                                <p>Pesanan Berhasil disimpan ke keranjang</p>
                                <a href="#" class="btn-lanjut" onclick="hideOverlay()">Lanjut Belanja</a>
                            </div>
                        </div>

                        <h2>Undangan Lainnya</h2>
                        <div class="product-container">
                            <div class="product-card">
                                <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
                                <p class="product-name">Undangan Khitanan</p>
                                <div class="description">
                                    <h4>Deskripsi Produk</h4>
                                    <p>
                                        kontidsbfdyjfvjdrvfrjbdjfraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                                    </p>
                                </div>
                                <p class="product-price">Rp. x.xxx,xx</p>
                                <a href="productdetail.php" class="detail-button"><img class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                                    <p>Lihat Detail</p>
                                </a>
                            </div>
                            <div class="product-card">
                                <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
                                <p class="product-name">Undangan Khitanan</p>
                                <div class="description">
                                    <h4>Deskripsi Produk</h4>
                                    <p>
                                        kontidsbfdyjfvjdrvfrjbdjfraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                                    </p>
                                </div>
                                <p class="product-price">Rp. x.xxx,xx</p>
                                <a href="productdetail.php" class="detail-button"><img class="cart-icon" src="../resources/img/icons/cart.png" alt="">
                                    <p>Lihat Detail</p>
                                </a>
                            </div>
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
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                    <div class="review-item">
                        <p><strong>Jane Smith</strong> - ⭐⭐⭐⭐</p>
                        <p>Kualitas produk oke, namun packaging bisa lebih baik lagi.</p>
                    </div>
                    <div class="review-item">
                        <p><strong>John Doe</strong> - ⭐⭐⭐⭐⭐</p>
                        <p>Produk yang sangat bagus dan sesuai dengan deskripsi. Pengiriman cepat!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../resources/js/thumnail.js"></script>
    <script src="../resources/js/overlay.js"></script>
    <script src="../resources/js/zoomimage.js"></script>
</body>

</html>
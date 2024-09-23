<?php
include '../databases/index.php'; // Panggil file koneksi database
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>
  <link rel="stylesheet" href="../resources/css/indexhomecsmr.css">
  <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
  <div class="container">
    <!-- Navbar -->
    <nav class="navbar">
      <?php include '../layout/cusmrLayout/navbar.php'; ?>
    </nav>

    <!-- SlidesShow Items Product -->
    <div class="slideshow-container">
      <div class="centered-text">
        <p class="top-text">Cetak Kartu Undangan Cepat & Berkualitas</p><br>
        <p class="bottom-text">Temukan KARTU UNDANGAN favorit Anda hanya di Website PleeArt.</p><br>
        <a href="#">Pesan Sekarang</a>
      </div>
      <div class="slides fade">
        <img src="../resources/img/icons/slides1.png" alt="Gambar 1">
      </div>

      <div class="slides fade">
        <img src="../resources/img/icons/slides2.png" alt="Gambar 2">
      </div>

      <div class="slides fade">
        <img src="../resources/img/icons/slides3.jpg" alt="Gambar 3">
      </div>

      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <div style="text-align:center">
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>
    </div>

    <!-- Items Product -->
    <div class="product-container">
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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
      <div class="product-card">
        <img class="product" src="../resources/img/icons/contohproduct.jpeg" alt="Undangan">
        <p class="product-name">Undangan Raya 36</p>
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

      <!-- Tambahkan produk lainnya sesuai kebutuhan -->
    </div>

    <!-- Footers Promotions -->
    <footer class="footer">
      <?php include '../layout/cusmrLayout/footer.php'; ?>
    </footer>
  </div>

  <script src="../resources/js/slides.js"></script>
  <script src="../resources/js/burgersidebar.js"></script>
</body>

</html>
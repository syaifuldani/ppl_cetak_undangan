<?php
require '../config/connection.php';
require_once '../config/function.php';

// Live Search
if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    searchProducts($searchTerm);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/dashboard.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
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

        <!-- Hero Section -->
        <section class="hero animate-slide-left">
            <h1 class="animate-fade-in animate-delay-1">Selamat datang di layanan Cetak Undangan Online kami!</h1>
            <p class="animate-fade-in animate-delay-2">
                Kami menyediakan berbagai template undangan yang siap Anda pilih sesuai dengan acara spesial Anda.
                Mulai dari undangan pernikahan yang elegan, undangan khitanan yang penuh makna, walimatul ursy yang
                istimewa,
                hingga undangan untuk acara tahlil dan kirim doa yang khusyuk, serta undangan ulang tahun yang meriah.
                Pilih desain favorit Anda, tambahkan detail acara, dan biarkan kami mencetak serta mengirimkannya
                langsung ke alamat Anda.
                Proses mudah, hasil memukau!
            </p>
        </section>

        <!-- Search Section -->
        <div class="section-search">
            <section class="search animate-slide-right">
                <h2 class="animate-fade-in animate-delay-1">
                    Temukan Beragam Desain Undangan Elegan
                </h2>
                <p class="animate-fade-in animate-delay-2">
                    Pesan Sekarang, Kami Kirimkan ke Alamat Anda
                </p>
                <form action="" method="POST" class="search-input animate-slide-right animate-delay-3">
                    <label><img src="../resources/img/icons/search.png" alt=""></label>
                    <input type="text" id="contentSearchBox" name="query" placeholder="Cari undanganMu"
                        value="<?= isset($_POST['query']) ? htmlspecialchars($_POST['query']) : '' ?>">
                </form>
                <div id="contentSearchResults" class="search-results animate-slide-bottom animate-delay-4">
                    <!-- Hasil pencarian dari konten index -->
                </div>
            </section>
            <div class="image animate-slide-left animate-delay-3">
                <img src="../resources/img/homeimg/promotion01.jpg" alt="">
            </div>
        </div>

        <!-- Product Section -->
        <section class="products animate-slide-bottom">
            <h2 class="animate-fade-in animate-delay-1">
                Pesan Undangan Menakjubkan dengan Mudah!
            </h2>
            <div class="product-grid">
                <div class="product-card-dsbrd animate-slide-top animate-delay-2">
                    <a href="undangan_pernikahan.php">
                        <img src="../resources/img/homeimg/pernikahan.jpg" alt="Undangan Pernikahan">
                        <p>Undangan Pernikahan</p>
                    </a>
                </div>
                <div class="product-card-dsbrd animate-slide-top animate-delay-3">
                    <a href="undangan_khitanan.php">
                        <img src="../resources/img/homeimg/khitanan.jpeg" alt="Undangan Khitan">
                        <p>Undangan Khitanan</p>
                    </a>
                </div>
                <div class="product-card-dsbrd animate-slide-top animate-delay-4">
                    <a href="undangan_walimatul.php">
                        <img src="../resources/img/homeimg/walimatul.jpg" alt="Undangan Walimah">
                        <p>Undangan Walimatul</p>
                    </a>
                </div>
                <div class="product-card-dsbrd animate-slide-top animate-delay-5">
                    <a href="undangan_tahlilkirimdoa.php">
                        <img src="../resources/img/homeimg/tahlilkirimdoa.jpg" alt="Undangan Tahlil & Doa">
                        <p>Undangan Tahlil & Doa</p>
                    </a>
                </div>
                <div class="product-card-dsbrd animate-slide-top animate-delay-6">
                    <a href="undangan_ulangtahun.php">
                        <img src="../resources/img/homeimg/ulangtahun.jpeg" alt="Undangan Ulang Tahun">
                        <p>Undangan Ulang Tahun</p>
                    </a>
                </div>
            </div>
        </section>


        <div class="layout-wrapper animate-slide-right animate-delay-3">
            <div class="instructions">
                <div class="step animate-slide-left animate-delay-1">
                    <img src="../resources/img/icons/checkaction.png" alt="Pilih Undangan" class="icon">
                    <div class="text">
                        <h3>Pilih Undangan</h3>
                        <p>Mulai Pesanan Anda dengan Langkah Mudah! Pilih desain undangan yang sesuai dengan acara Anda
                            dari berbagai template yang kami sediakan.</p>
                    </div>
                </div>
                <div class="step animate-slide-left animate-delay-1">
                    <img src="../resources/img/icons/cartaction.png" alt="Tambahkan ke Keranjang Belanja" class="icon">
                    <div class="text">
                        <h3>Tambahkan ke Keranjang Belanja</h3>
                        <p>Isi semua detail penting, seperti nama, tanggal acara, dan pesan khusus yang ingin
                            dicantumkan di undangan. Jangan lupa untuk memasukkan alamat pengiriman.</p>
                    </div>
                </div>
                <div class="step animate-slide-left animate-delay-1">
                    <img src="../resources/img/icons/payaction.png" alt="Pilih Metode Pembayaran" class="icon">
                    <div class="text">
                        <h3>Pilih Metode Pembayaran</h3>
                        <p>Pilih berbagai metode pembayaran yang Anda inginkan dan tunggu pesanan Anda sampai sesuai
                            alamat tujuan.</p>
                    </div>
                </div>
            </div>
            <div class="preview slide-in-bottom">
                <img src="../resources/img/homeimg/promotion02.jpg" alt="Preview Undangan" class="preview-image">
            </div>
        </div>
    </div>

    <!-- Footers Promotions -->
    <footer class="footer animate-slide-top animate-delay-2">
        <?php include 'layout/cusmrLayout/footer.php'; ?>
    </footer>
    </div>

    <script src="../resources/js/burgersidebar.js"></script>
    <script src="../resources/js/livesearch.js"></script>
</body>

</html>
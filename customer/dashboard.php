<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../resources/css/dashboard.css">
  <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
  <div class="container">
    <!-- Navbar -->
    <nav class="navbar">
      <?php include 'layout/cusmrLayout/navbar.php'; ?>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <h1>Selamat datang di layanan Cetak Undangan Online kami!</h1>
      <p>
        Kami menyediakan berbagai template undangan yang siap Anda pilih sesuai dengan acara spesial Anda.
        Mulai dari undangan pernikahan yang elegan, undangan khitanan yang penuh makna, walimatul ursy yang istimewa,
        hingga undangan untuk acara tahlil dan kirim doa yang khusyuk, serta undangan ulang tahun yang meriah.
        Pilih desain favorit Anda, tambahkan detail acara, dan biarkan kami mencetak serta mengirimkannya langsung ke alamat Anda.
        Proses mudah, hasil memukau!
      </p>
    </section>

    <!-- Search Section -->
    <div class="section-search">
      <section class="search">
        <h2>Temukan Beragam Desain Undangan Elegan</h2>
        <p>Pesan Sekarang, Kami Kirimkan ke Alamat Anda</p>
        <form action="" class="search">
          <input type="text" placeholder="Cari undanganMu">
          <button type="submit"><img src="../resources/img/icons/search.png" alt=""></img></button>
        </form>
      </section>
      <div class="image">
        <img src="../resources/img/homeimg/promotion01.jpg" alt="">
      </div>
    </div>

    <!-- Product Section -->
    <section class="products">
      <h2>Pesan Undangan Menakjubkan dengan Mudah!</h2>
      <div class="product-grid">
        <div class="product-card-dsbrd">
          <a href="#">
            <img src="../resources/img/homeimg/pernikahan.jpg" alt="Undangan Pernikahan">
            <p>Undangan Pernikahan</p>
          </a>
        </div>
        <div class="product-card-dsbrd">
          <a href="#">
            <img src="../resources/img/homeimg/khitanan.jpeg" alt="Undangan Khitan">
            <p>Undangan Khitan</p>
          </a>
        </div>
        <div class="product-card-dsbrd">
          <a href="#">
            <img src="../resources/img/homeimg/walimatul.jpg" alt="Undangan Walimah">
            <p>Undangan Walimatul</p>
          </a>
        </div>
        <div class="product-card-dsbrd">
          <a href="#">
            <img src="../resources/img/homeimg/tahlilkirimdoa.jpg" alt="Undangan Tahlil & Doa">
            <p>Undangan Tahlil & Doa</p>
          </a>
        </div>
        <div class="product-card-dsbrd">
          <a href="#">
            <img src="../resources/img/homeimg/ulangtahun.jpeg" alt="Undangan Ulang Tahun">
            <p>Undangan Ulang Tahun</p>
          </a>
        </div>
      </div>
    </section>

    <div class="layout-wrapper">
      <div class="instructions">
        <div class="step">
          <img src="../resources/img/icons/checkaction.png" alt="Pilih Undangan" class="icon">
          <div class="text">
            <h3>Pilih Undangan</h3>
            <p>Mulai Pesanan Anda dengan Langkah Mudah! Pilih desain undangan yang sesuai dengan acara Anda dari berbagai template yang kami sediakan.</p>
          </div>
        </div>
        <div class="step">
          <img src="../resources/img/icons/cartaction.png" alt="Tambahkan ke Keranjang Belanja" class="icon">
          <div class="text">
            <h3>Tambahkan ke Keranjang Belanja</h3>
            <p>Isi semua detail penting, seperti nama, tanggal acara, dan pesan khusus yang ingin dicantumkan di undangan. Jangan lupa untuk memasukkan alamat pengiriman.</p>
          </div>
        </div>
        <div class="step">
          <img src="../resources/img/icons/payaction.png" alt="Pilih Metode Pembayaran" class="icon">
          <div class="text">
            <h3>Pilih Metode Pembayaran</h3>
            <p>Pilih berbagai metode pembayaran yang Anda inginkan dan tunggu pesanan Anda sampai sesuai alamat tujuan.</p>
          </div>
        </div>
      </div>
      <div class="preview">
        <img src="../resources/img/homeimg/promotion02.jpg" alt="Preview Undangan" class="preview-image">
      </div>
    </div>
  </div>

  <!-- Footers Promotions -->
  <footer class="footer">
    <?php include 'layout/cusmrLayout/footer.php'; ?>
  </footer>
  </div>

  <script src="../resources/js/slides.js"></script>
  <script src="../resources/js/burgersidebar.js"></script>
</body>

</html>
<?php
require '../config/connection.php';
require '../config/function.php';

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
    <title>Terms of Use</title>
    <link rel="stylesheet" href="../resources/css/dashboard.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/termofuse.css">

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

        <!-- Konten Utama -->
        <div class="content">
            <h1>Syarat dan Ketentuan</h1>
            <p>Selamat datang di situs web cetak undangan online kami. Dengan mengakses dan menggunakan situs ini, Anda setuju untuk mematuhi syarat dan ketentuan berikut. Jika Anda tidak setuju dengan syarat ini, harap untuk tidak menggunakan layanan kami.</p>

            <h2>1. Penggunaan Layanan</h2>
            <p>Layanan kami ditujukan untuk memudahkan pengguna dalam merancang dan mencetak undangan secara online. Pengguna bertanggung jawab atas keakuratan dan kebenaran data yang dimasukkan dalam proses pembuatan undangan. Layanan hanya boleh digunakan untuk tujuan yang sah dan tidak melanggar hukum.</p>

            <h2>2. Pendaftaran dan Akun Pengguna</h2>
            <p>Pengguna diwajibkan untuk mendaftarkan akun dengan memberikan informasi yang akurat, termasuk nama, alamat, nomor telepon, dan email. Anda bertanggung jawab atas keamanan akun Anda dan menjaga kerahasiaan informasi login. Kami berhak menonaktifkan akun pengguna jika ditemukan pelanggaran terhadap syarat dan ketentuan ini.</p>

            <h2>3. Kebijakan Pemesanan dan Pembayaran</h2>
            <p>Semua pesanan undangan harus melalui proses pembayaran yang sah melalui metode yang disediakan di situs. Setelah pesanan dikonfirmasi dan pembayaran diterima, kami akan mulai memproses undangan Anda sesuai spesifikasi yang dipilih. Pembatalan atau perubahan pada pesanan hanya dapat dilakukan sebelum undangan dicetak.</p>

            <h2>4. Hak Kekayaan Intelektual</h2>
            <p>Semua konten, desain, dan template yang tersedia di situs ini merupakan milik kami dan dilindungi oleh hukum hak cipta. Pengguna tidak diperkenankan menyalin, mendistribusikan, atau memodifikasi konten dari situs ini tanpa izin tertulis dari kami.</p>

            <h2>5. Kebijakan Privasi</h2>
            <p>Kami mengumpulkan data pribadi yang diperlukan untuk memproses pesanan Anda, dan kami berkomitmen untuk menjaga kerahasiaan dan keamanan data Anda. Informasi lebih lanjut tentang bagaimana kami menangani data pribadi Anda dapat ditemukan di Kebijakan Privasi kami.</p>

            <h2>6. Pembatasan Tanggung Jawab</h2>
            <p>Kami tidak bertanggung jawab atas kesalahan ketik, desain yang tidak sesuai dengan keinginan pengguna, atau keterlambatan pengiriman yang disebabkan oleh kesalahan pengguna atau pihak ketiga. Kami akan berusaha memastikan layanan berjalan tanpa gangguan, namun kami tidak dapat menjamin akses tanpa kesalahan atau gangguan teknis.</p>

            <h2>7. Perubahan Layanan dan Syarat</h2>
            <p>Kami berhak untuk melakukan perubahan pada layanan, harga, dan syarat ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Pengguna disarankan untuk selalu memeriksa syarat dan ketentuan terbaru sebelum menggunakan layanan kami.</p>

            <h2>8. Hukum yang Berlaku</h2>
            <p>Syarat dan ketentuan ini diatur dan ditafsirkan sesuai dengan hukum yang berlaku di Indonesia. Segala perselisihan yang timbul dari penggunaan situs ini akan diselesaikan melalui mediasi terlebih dahulu, dan jika tidak tercapai kesepakatan, maka akan diselesaikan di pengadilan setempat.</p>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <?php include 'layout/cusmrLayout/footer.php'; ?>
        </footer>
    </div>

    <script src="../resources/js/slides.js"></script>
    <script src="../resources/js/burgersidebar.js"></script>
</body>

</html>
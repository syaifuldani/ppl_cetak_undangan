<?php
// Memulai session
session_start();

// Inklusi file fungsi untuk mengambil data keranjang
include '../config/function.php';

// Memastikan pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Cek apakah ada permintaan untuk menghapus item dari keranjang
    if (isset($_GET['product_id'])) {
        $productId = $_GET['product_id'];

        // Panggil fungsi deleteCartItems untuk menghapus item
        deleteCartItems($userId, $productId);

        // Redirect kembali ke halaman keranjang setelah penghapusan
        header("Location: cart.php");
        exit(); // Stop eksekusi script setelah redirect
    }

    // Ambil item keranjang dari database
    $cartItems = getCartItems($userId);
} else {
    // Set status HTTP menjadi 404 (Not Found)
    http_response_code(404);

    // Tampilkan halaman "Page Not Found"
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Halaman yang Anda minta tidak ditemukan</p>";
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $cartId = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;
    if ($cartId > 0) {
        deleteCartItems($userId, $cartId);
    } else {
        echo "Produk tidak valid.";
    }
}

// Mendapatkan data item dari database
$cartItems = getCartItems($userId);

// Mendapatkan user_id dari session
$userId = $_SESSION['user_id'];

// Memanggil fungsi untuk mendapatkan item keranjang
$cartItems = getCartItems($userId);

// Fungsi untuk memperbarui keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $cartId => $quantity) {
        // Memperbarui jumlah produk di database
        updateCartItem($userId, $cartId, $quantity);
    }

    // Refresh halaman setelah pembaruan
    header("Location: cart.php?updated=success");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/cart.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <div class="content">
            <div class="cart-container">
                <div class="cart-section">
                    <h1>Keranjang Anda!</h1>
                    <!-- Form untuk update keranjang -->
                    <form action="cart.php" method="POST">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Harga Per Kertas</th>
                                    <th>Jumlah</th>
                                    <th>Sub Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cartItems)) : ?>
                                    <?php foreach ($cartItems as $item) : ?>
                                        <tr>
                                            <td>
                                                <div class="items">
                                                    <img alt="Product Image" class="product-image" height="50" src="<?= $item['gambar_satu']; ?>" width="50" />
                                                    <p><?= $item['nama_produk']; ?></p>
                                                </div>
                                            </td>
                                            <td class="price">Rp.<?= number_format($item['harga_produk'], 2, ',', '.'); ?></td>
                                            <td>
                                                <div class="quantity-control">
                                                    <button type="button" onclick="decreaseQuantity(<?= $item['cart_id']; ?>)">-</button>
                                                    <input type="text" name="quantities[<?= $item['product_id']; ?>]" value="<?= $item['jumlah']; ?>" min="1" id="quantityInput-<?= $item['cart_id']; ?>">
                                                    <button type="button" onclick="increaseQuantity(<?= $item['cart_id']; ?>)">+</button>
                                                </div>
                                            </td>
                                            <td class="subtotal">Rp.<?= number_format($item['jumlah'] * $item['harga_produk'], 2, ',', '.'); ?></td>
                                            <td>
                                                <a href="cart.php?action=delete&cart_id=<?= $item['cart_id']; ?>" class="delete-item">
                                                    <img src="../resources/img/icons/trash.png" alt="Hapus Item">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">Keranjang kosong.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- Tombol untuk memperbarui keranjang -->
                        <div class="update-cart-btn">
                            <input type="hidden" name="product_id" value="<?php echo isset($item['product_id']) ? $item['product_id'] : ''; ?>">
                            <button type="submit" name="update_cart" class="update-cart-btn">Perbarui Keranjang</button>
                        </div>
                    </form>
                    <div class="warning-message">
                        Lengkapi Data Undangan dan Data Pengiriman Anda!!
                    </div>
                    <div class="form-section">
                        <div class="form-group">
                            <h3>Data Undangan</h3>
                            <input type="date" placeholder="Tanggal dan Waktu Acara">
                            <input type="text" placeholder="Tempat/Lokasi Acara">
                            <textarea placeholder="Keterangan Tambahan"></textarea>
                            <p class="info">
                                Tuliskan keterangan tambahan seperti nama orang tua dan calon mempelai, teks doa, nama yang dirayakan, tema acara, atau pesan/informasi penting lainnya sesuai dengan acara pernikahan, khitan, walimatul ursy, tahlil, kirim doa, atau ulang tahun.<br><br>

                                Contoh : <br>
                                Nama : John Doe <br>
                                Teks Doa: "Semoga diberikan keberkahan dan keselamatan dunia akhirat." <br>
                                Dst.
                            </p>
                        </div>
                        <div class="form-group">
                            <h3>Data Alamat Kirim</h3>
                            <input type="text" placeholder="Nama Lengkap Penerima">
                            <input type="text" placeholder="No. Telp Penerima">
                            <textarea placeholder="Alamat Lengkap dan Keterangan"></textarea>
                            <p class="info">
                                Pastikan alamat yang Anda tulis lengkap dan jelas, termasuk nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan, kota/kabupaten, dan kode pos.
                                Jangan lupa sertakan informasi tambahan seperti patokan lokasi (misalnya: "Di sebelah toko A" atau "Dekat dengan kantor B") agar paket dapat dikirimkan dengan tepat. <br><br>
                                Contoh: <br>
                                Nama: John Doe <br>
                                Alamat: Jl. A Yani No. 123, RT 02/RW 03, Dsn.Sumberjo Ds.Sumbertanggul Kec. Mojosari Kab. Mojokerto, 41382 <br>
                                Patokan: Rumah warna putih depannya ada pohon sawo.
                            </p>
                        </div>
                        <button class="pay-btn">Bayar Sekarang</button>
                    </div>
                </div>

                <div class="details-section">
                    <div class="order-history">
                        <h3>Riwayat Pemesanan</h3>
                        <ul>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="riwayat_pemesanan.php">Lihat →</a>
                            </li>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="riwayat_pemesanan.php">Lihat →</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Overlay Konfirmasi -->
            <div id="deleteOverlay" class="overlay" style="display: none;">
                <div class="overlay-content">
                    <p>Apakah Anda yakin ingin menghapus item ini?</p>
                    <button id="confirmDelete" class="btn-confirm">Ya, Hapus</button>
                    <button id="cancelDelete" class="btn-cancel">Batal</button>
                </div>
            </div>

            <!-- Overlay Konfirmasi Pembaruan Keranjang -->
            <div id="updateOverlay" class="overlay" style="display: none;">
                <div class="overlay-content">
                    <p>Keranjang berhasil diperbarui!</p>
                    <button id="closeUpdateOverlay" class="btn-cancel">OK</button>
                </div>
            </div>


        </div>
    </div>

    <script src="../resources/js/burgersidebar.js"></script>
    <script>
        const deleteOverlay = document.getElementById('deleteOverlay');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        let deleteUrl = '';

        // Tambahkan event listener pada tombol hapus
        document.querySelectorAll('.delete-item').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                deleteUrl = this.getAttribute('href'); // Simpan URL penghapusan
                deleteOverlay.style.display = 'flex'; // Tampilkan overlay
            });
        });

        // Tombol konfirmasi penghapusan
        confirmDeleteBtn.addEventListener('click', function() {
            window.location.href = deleteUrl; // Arahkan ke URL penghapusan
        });

        // Tombol batal
        cancelDeleteBtn.addEventListener('click', function() {
            deleteOverlay.style.display = 'none'; // Sembunyikan overlay
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const updateOverlay = document.getElementById('updateOverlay');
            const closeUpdateOverlayBtn = document.getElementById('closeUpdateOverlay');

            if (urlParams.has('updated') && urlParams.get('updated') === 'success') {
                updateOverlay.style.display = 'flex';
            }

            // Tutup overlay saat tombol "OK" ditekan
            closeUpdateOverlayBtn.addEventListener('click', function() {
                updateOverlay.style.display = 'none';
                // Menghapus parameter dari URL
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        });
    </script>
    <script>
        function increaseQuantity(cartId) {
            const quantityInput = document.getElementById(`quantityInput-${cartId}`);
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1; 
        }

        function decreaseQuantity(cartId) {
            const quantityInput = document.getElementById(`quantityInput-${cartId}`);
            let currentValue = parseInt(quantityInput.value);

            if (currentValue > 1) {
                quantityInput.value = currentValue - 1; 
            }
        }
    </script>
</body>

</html>
<?php
// Di PHP, sebelum memulai session
session_start([
    'cookie_secure' => true,
    'cookie_samesite' => 'Lax'
]);

// Security Headers
function setSecurityHeaders()
{
    // Protect against clickjacking
    header("X-Frame-Options: SAMEORIGIN");

    // Protect against XSS and other injections
    // Update Content Security Policy untuk mengizinkan cdnjs
    header("Content-Security-Policy: default-src 'self' https://*.midtrans.com; script-src 'self' https://*.midtrans.com https://cdnjs.cloudflare.com 'unsafe-inline' 'unsafe-eval';  style-src 'self' 'unsafe-inline';  img-src 'self' data: https:;  frame-src https://*.midtrans.com");

    // Prevent MIME-type sniffing
    header("X-Content-Type-Options: nosniff");

    // Enable XSS protection
    header("X-XSS-Protection: 1; mode=block");

    // Use HTTPS only
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

    // Prevent browsers from sending referrer information
    header("Referrer-Policy: same-origin");
}

// Gunakan function di atas
setSecurityHeaders();

// Inklusi file fungsi untuk mengambil data keranjang
require_once '../config/function.php';
require_once '../config/midtrans_config.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fungsi untuk memperbarui keranjang
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
        if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
            $success = updateCartItem($userId, $_POST['quantities']);

            if ($success) {
                header("Location: cart.php?updated=success");
            } else {
                header("Location: cart.php?error=update_failed");
            }
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST['shipping_data'] = [
            'courier' => $_POST['shipping_courier'],
            'service' => $_POST['shipping_service'],
            'cost' => $_POST['shipping_cost'],
            'eta' => $_POST['shipping_eta']
        ];

        $response = payment_handled($_POST, $userId);
    }

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
    $cartId = isset($_GET['cart_id']) ? (int) $_GET['cart_id'] : 0;
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

// Live Search
if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    searchProducts($searchTerm);
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../resources/css/cart.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key=<?php $_ENV['MIDTRANS_CLIENT_KEY'] ?> crossorigin="anonymous" importance="high" async></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
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
            <div class="cart-container">
                <div class="cart-section">
                    <h1>Keranjang Anda!</h1>
                    <!-- Form untuk update keranjang -->
                    <form action="" method="POST">
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
                                <?php if (!empty($cartItems)): ?>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="items">
                                                    <img alt="Product Image" class="product-image" height="50"
                                                        src="<?= $item['gambar_satu']; ?>" width="50" />
                                                    <p><?= $item['nama_produk']; ?></p>
                                                </div>
                                            </td>
                                            <td class="price">Rp.<?= number_format($item['harga_produk'], 2, ',', '.'); ?></td>
                                            <td>
                                                <div class="quantity-control">
                                                    <button type="button"
                                                        onclick="decreaseQuantity(<?= $item['cart_id']; ?>)">-</button>
                                                    <input type="text"
                                                        name="quantities[<?= htmlspecialchars($item['product_id']); ?>]"
                                                        value="<?= htmlspecialchars($item['jumlah']); ?>" min="1"
                                                        id="quantityInput-<?= $item['cart_id']; ?>">
                                                    <button type="button"
                                                        onclick="increaseQuantity(<?= $item['cart_id']; ?>)">+</button>
                                                </div>
                                            </td>
                                            <td class="subtotal">
                                                Rp.<?= number_format($item['jumlah'] * $item['harga_produk'], 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <a href="?action=delete&cart_id=<?= htmlspecialchars($item['cart_id']); ?>"
                                                    class="delete-item">
                                                    <img src="../resources/img/icons/trash.png" alt="Hapus Item">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">Keranjang kosong.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- Tombol untuk memperbarui keranjang -->
                        <div class="update-cart-btn">
                            <button type="submit" name="update_cart" class="update-cart-btn">Perbarui Keranjang</button>
                        </div>
                    </form>

                    <!-- Form untuk payment -->
                    <div class="warning-message">
                        Lengkapi Data Undangan dan Data Pengiriman Anda!!
                    </div>

                    <form id="payment-form" action="" method="POST">
                        <!-- Hidden inputs akan ditambahkan secara dinamis oleh JavaScript -->
                        <input type="hidden" name="shipping_cost" value="">
                        <input type="hidden" name="shipping_eta" value="">
                        <input type="hidden" name="shipping_courier" value="">
                        <input type="hidden" name="shipping_service" value="">
                        <div class="form-section">
                            <!-- Tambahkan hidden input untuk snap token -->
                            <input type="hidden" name="snap_token" id="snap-token">
                            <div class="form-group">
                                <h3>Data Undangan</h3>
                                <input type="date" name="tanggalacara" placeholder="Tanggal dan Waktu Acara">
                                <input type="text" name="lokasiacara" placeholder="Tempat/Lokasi Acara">
                                <textarea name="keterangan_order" placeholder="Keterangan Tambahan"></textarea>
                                <p class="info">
                                    Tuliskan keterangan tambahan seperti nama orang tua dan calon mempelai, teks
                                    doa,
                                    nama
                                    yang dirayakan, tema acara, atau pesan/informasi penting lainnya sesuai dengan
                                    acara
                                    pernikahan, khitan, walimatul ursy, tahlil, kirim doa, atau ulang tahun.<br><br>

                                    Contoh : <br>
                                    Nama : John Doe <br>
                                    Teks Doa: "Semoga diberikan keberkahan dan keselamatan dunia akhirat." <br>
                                    Dst.
                                </p>
                            </div>
                            <div class="shipping-form">
                                <h3>Data Alamat Kirim</h3>

                                <div class="form-grid">
                                    <!-- Kolom Kiri -->
                                    <div class="form-column">
                                        <div class="form-group">
                                            <label>Nama Penerima</label>
                                            <input type="text" name="namapenerima" placeholder="Nama Lengkap Penerima">
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" placeholder="Email">
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor Telepon</label>
                                            <input type="text" name="notelppenerima" placeholder="+628123456789">
                                            <small class="helper-text">Diawali dengan +62</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select name="provinsi" id="provinsi">
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kabupaten/Kota</label>
                                            <select name="kota" id="kota" disabled>
                                                <option value="">Pilih Kabupaten/Kota</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <input type="text" name="kecamatan" placeholder="Kecamatan">
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="form-column">
                                        <div class="form-group">
                                            <label>Kelurahan/Desa</label>
                                            <input type="text" name="kelurahan" placeholder="Kelurahan/Desa">
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Lengkap</label>
                                            <textarea name="alamatpenerima"
                                                placeholder="Nama jalan, nomor rumah, RT/RW, patokan"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input type="text" name="kodepos" placeholder="Kode Pos" pattern="[0-9]{5}">
                                        </div>
                                        <div class="form-group">
                                            <label>Pilih Kurir</label>
                                            <select name="courier" id="courier">
                                                <option value="">Pilih Kurir</option>
                                                <option value="jne">JNE</option>
                                                <!-- <option value="pos">POS Indonesia</option>
                                                <option value="tiki">TIKI</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button cek ongkir di bawah grid -->
                                <div class="form-actions">
                                    <button type="button" id="check-shipping" class="btn-check-shipping">
                                        Cek Ongkir
                                    </button>
                                </div>

                                <!-- Hasil cek ongkir -->
                                <div id="shipping-results" class="shipping-results"></div>
                            </div>
                            <button class="pay-btn" id="pay-btn">Bayar Sekarang</button>
                        </div>
                    </form>
                </div>

                <div id=" snap-container">
                </div>

                <div class="details-section">
                    <div class="order-history">
                        <h3>Pesanan Saya</h3>
                        <ul>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="pesanan_saya.php">Lihat →</a>
                            </li>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="pesanan_saya.php">Lihat →</a>
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
            button.addEventListener('click', function (e) {
                e.preventDefault();
                deleteUrl = this.getAttribute('href'); // Simpan URL penghapusan
                deleteOverlay.style.display = 'flex'; // Tampilkan overlay
            });
        });

        // Tombol konfirmasi penghapusan
        confirmDeleteBtn.addEventListener('click', function () {
            window.location.href = deleteUrl; // Arahkan ke URL penghapusan
        });

        // Tombol batal
        cancelDeleteBtn.addEventListener('click', function () {
            deleteOverlay.style.display = 'none'; // Sembunyikan overlay
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const updateOverlay = document.getElementById('updateOverlay');
            const closeUpdateOverlayBtn = document.getElementById('closeUpdateOverlay');

            if (urlParams.has('updated') && urlParams.get('updated') === 'success') {
                updateOverlay.style.display = 'flex';
            }

            // Tutup overlay saat tombol "OK" ditekan
            closeUpdateOverlayBtn.addEventListener('click', function () {
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
    <script src="..\resources\js\Order.js"></script>
    <script src="..\resources\js\CheckOngkir.js"></script>
    <script src="..\resources\js\validateInputCart.js"></script>
</body>

</html>
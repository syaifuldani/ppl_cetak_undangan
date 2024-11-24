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

require_once '../config/function.php';
require_once '../config/midtrans_config.php';

// Memastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id'];
$orders = getOrdersByID($userId);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <!-- // Cetak Note Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="../resources/css/cart.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/pesanan_saya.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key=<?php $_ENV['MIDTRANS_CLIENT_KEY'] ?> crossorigin="anonymous" importance="high" async></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
    <script src="../resources/js/alert-detailorder-admin.js"></script>
</head>

<body>

    <div class="container">

        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <div id="alert-container"></div>

        <div class="pesanan-container">
            <h2>Pesanan Saya</h2>

            <!-- Tab Navigation -->
            <div class="status-tabs-container">
                <nav class="status-tabs" role="tablist">
                    <button class="tab-button" data-status="pending" role="tab">
                        Perlu Dibayar
                    </button>
                    <button class="tab-button" data-status="settlement" role="tab">
                        Dibayar
                    </button>
                    <button class="tab-button" data-status="processing" role="tab">
                        Dikemas
                    </button>
                    <button class="tab-button" data-status="shipped" role="tab">
                        Dikirim
                    </button>
                    <button class="tab-button" data-status="delivered" role="tab">
                        Selesai
                    </button>
                    <button class="tab-button" data-status="cancelled" role="tab">
                        Dibatalkan
                    </button>
                </nav>
            </div>

            <!-- Orders Container -->
            <div class="orders-container">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card" data-status="<?= $order['transaction_status'] ?>">
                        <div class="order-header">
                            <div class="order-date">
                                <i class="fas fa-calendar"></i>
                                <?= date('d F Y', strtotime($order['created_at'])) ?>
                            </div>
                            <div class="order-status <?= strtolower($order['transaction_status']) ?>">
                                <span
                                    class="order-status status-<?= !empty($order['transaction_status']) ? strtolower($order['transaction_status']) : 'pending' ?>">
                                    <?= getStatusLabel($order['transaction_status']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-body">
                            <div class="order-items">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="item">
                                        <img src="<?= $item['gambar_satu'] ?>" alt="<?= $item['nama_produk'] ?>"
                                            class="item-image">
                                        <div class="item-details">
                                            <h4><?= $item['nama_produk'] ?></h4>
                                            <p><?= $item['jumlah_order'] ?> x Rp
                                                <?= number_format($item['harga_order'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="order-info">
                                <div class="total-items">
                                    <?= count($order['items']) ?> Produk
                                </div>
                                <div class="total-price">
                                    Total Pesanan: <span>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="order-footer">
                            <?php if ($order['transaction_status'] == 'pending'): ?>
                                <button class="btn-pay" onclick="payOrder('<?= htmlspecialchars($order['order_id']) ?>')"
                                    data-order-id="<?= htmlspecialchars($order['order_id']) ?>" type="button">
                                    Bayar Sekarang
                                </button>
                            <?php elseif ($order['transaction_status'] == 'shipped'): ?>
                                <button class="btn-receive" onclick="return handleReceived(this, '<?= $order['order_id'] ?>')"
                                    data-order-id="<?= $order['order_id'] ?>" type="button">
                                    Pesanan Diterima
                                </button>
                            <?php endif; ?>
                            <button class="btn-details" onclick="viewOrderDetails('<?= $order['order_id'] ?>')">Lihat
                                Detail</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Tambahkan di bagian akhir file sebelum closing body tag -->
        <div id="orderDetailModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <div id="orderDetailContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            showAlert('<?= $_SESSION['success'] ?>', 'success');
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            showAlert('<?= $_SESSION['error'] ?>', 'error');
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        function handleReceived(button, orderId) {
            console.log('Handling order:', orderId);
            if (confirm('Apakah Anda yakin pesanan sudah diterima?')) {
                button.disabled = true;
                button.innerHTML = 'Memproses...';

                const formData = new FormData();
                formData.append('order_id', orderId);
                formData.append('status', 'delivered');

                fetch('../config/updateStatusAfterDelivered.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showAlert('Pesanan berhasil dikonfirmasi diterima', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showAlert(data.message || 'Gagal mengupdate status', 'error');
                            button.disabled = false;
                            button.innerHTML = 'Pesanan Diterima';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Terjadi kesalahan saat memproses permintaan', 'error');
                        button.disabled = false;
                        button.innerHTML = 'Pesanan Diterima';
                    });
            }
            return false;
        }
    </script>
    <script src="..\resources\js\LihatDetailPesananCust.js"></script>
    <script src="..\resources\js\Order.js"></script>
    <script src="..\resources\js\CetakNota.js"></script>

</body>

</html>
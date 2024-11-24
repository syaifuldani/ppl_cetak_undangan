<?php
// success.php
require_once '../../config/connection.php';
require_once '../../config/midtrans_config.php';

session_start();

if (!isset($_GET['order_id'])) {
    header('Location: ../cart.php');
    exit;
}

try {
    $order_id = $_GET['order_id'];

    // Verifikasi order belongs to current user
    $sql = "SELECT o.*, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.user_id 
            WHERE o.order_id = ? AND o.user_id = ?";

    $stmt = $db->prepare($sql);
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception('Order tidak ditemukan');
    }

    // Get order items
    $sql = "SELECT od.*, p.nama_produk, p.gambar_satu 
            FROM order_details od
            JOIN products p ON od.product_id = p.product_id
            WHERE od.order_id = ?";

    $stmt = $db->prepare($sql);
    $stmt->execute([$order_id]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get payment result from session storage (optional)
    $paymentResult = isset($_SESSION['payment_result']) ? $_SESSION['payment_result'] : null;

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="../../resources/css/pendinghandling.css">
</head>

<body>
    <div class="pending-container">
        <div class="pending-card">
            <div class="pending-header">
                <img src="../../resources/img/pending.png" alt="pending" class="pending-icon">
                <h1>Pesananmu Pending</h1>
                <p>Pesanan Anda telah dikonfirmasi</p>
            </div>

            <div class="order-details">
                <h2>Detail Pesanan</h2>
                <div class="order-info">
                    <div class="info-row">
                        <span>Order ID:</span>
                        <span><?= htmlspecialchars($order['order_id']) ?></span>
                    </div>
                    <div class="info-row">
                        <span>Total Pembayaran:</span>
                        <span>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="info-row">
                        <span>Metode Pembayaran:</span>
                        <span><?= htmlspecialchars($order['payment_type'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span>Waktu Pembayaran:</span>
                        <span><?= date('d F Y H:i', strtotime($order['payment_time'] ?? 'now')) ?></span>
                    </div>
                </div>

                <div class="shipping-details">
                    <h3>Informasi Pengiriman</h3>
                    <p>
                        <strong>Penerima:</strong> <?= htmlspecialchars($order['nama_penerima']) ?><br>
                        <strong>Alamat:</strong> <?= htmlspecialchars($order['alamat_penerima']) ?><br>
                        <strong>No. Telepon:</strong> <?= htmlspecialchars($order['nomor_penerima']) ?>
                    </p>
                </div>

                <div class="order-items">
                    <h3>Produk yang Dipesan</h3>
                    <?php foreach ($orderItems as $item): ?>
                        <div class="item-card">
                            <img src="<?= htmlspecialchars($item['gambar_satu']) ?>"
                                alt="<?= htmlspecialchars($item['nama_produk']) ?>" class="item-image">
                            <div class="item-info">
                                <h4><?= htmlspecialchars($item['nama_produk']) ?></h4>
                                <p>Jumlah: <?= $item['jumlah_order'] ?></p>
                                <p>Harga: Rp <?= number_format($item['harga_order'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="loadingSpinner" style="display: none;">
                <div class="spinner"></div>
                <p>Memproses pembayaran...</p>
            </div>

            <div class="pending-footer">
                <a href="../../customer/dashboard.php" class="btn-primary">Kembali ke Dashboard</a>
                <a href="../../customer/pesanan_saya.php" class="btn-secondary">Lihat Pesanan Saya</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil payment result dari sessionStorage jika ada
            const paymentResult = sessionStorage.getItem('paymentResult');
            if (paymentResult) {
                const result = JSON.parse(paymentResult);
                // console.log('Payment Result:', result);

                // Bersihkan sessionStorage
                sessionStorage.removeItem('paymentResult');
            }
        });
    </script>
</body>

</html>
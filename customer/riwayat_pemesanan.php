<?php
// Memulai session
session_start();

// Memastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/function.php';
$userId = $_SESSION['user_id'];

// Mengambil data riwayat pesanan pengguna
// $orders = getOrderHistory($userId);

// function formatTanggal($tanggal) {
//     return date("d M Y", strtotime($tanggal));
// }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link rel="stylesheet" href="../resources/css/riwayat_pemesanan.css">
</head>
<body>

<div class="container">
    <h1>Riwayat Pesanan</h1>

    <?php if (!empty($orders)) : ?>
        <div class="order-list">
            <?php foreach ($orders as $order) : ?>
                <div class="order-item">
                    <div class="order-header">
                        <span class="order-date"><?php //formatTanggal($order['tanggal']); ?></span>
                        <span class="order-status"><?= $order['status']; ?></span>
                    </div>
                    <div class="order-details">
                        <p><strong>ID Pesanan:</strong> <?= $order['order_id']; ?></p>
                        <p><strong>Total:</strong> Rp<?= number_format($order['total_harga'], 2, ',', '.'); ?></p>
                        <a href="orderdetail.php?order_id=<?= $order['order_id']; ?>" class="order-detail-link">Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Anda belum memiliki riwayat pesanan.</p>
    <?php endif; ?>
</div>

</body>
</html>

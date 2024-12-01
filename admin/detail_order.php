<?php
// detail_order.php
session_start();
$jenishalaman = 'Detail Order';
require_once '../config/connection.php';
require_once '../config/function.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'admin') {
    header('Location: login_admin.php');
    exit;
}
// var_dump($_SESSION['user_id']);
// Ambil order_id dari parameter URL
$order_id = $_GET['order_id'] ?? null;
// var_dump($order_id);

if (isset($_POST['update_resi'])) {
    $result = updateResi($_POST['order_id'], $_POST['nomor_resi']);

    if ($result['success']) {
        echo "<script>
            alert('Nomor resi berhasil diupdate');
            window.location.href = window.location.href.split('?')[0] + '?order_id=" . $order_id . "';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Error: Gagal mengupdate nomor resi - " . $e->getMessage() . "');
            window.history.back();
        </script>";
        exit;
    }
}


if (!$order_id) {
    die('Order ID tidak valid');
}

// Di bagian atas file, setelah session_start()
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'] ?? '';
    $newStatus = $_POST['status'] ?? '';

    $result = updateStatusByOrderId($orderId, $newStatus);

    if ($result['status'] === 'success') {
        $_SESSION['success'] = $result['message'];
        // Redirect untuk refresh halaman
        header("Location: " . $_SERVER['PHP_SELF'] . "?order_id=" . $orderId);
        exit;
    } else {
        $_SESSION['error'] = $result['message'];
    }
}

try {
    // Get order data
    $stmt = $db->prepare("
        SELECT o.*, u.nama_lengkap, u.email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        WHERE o.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get order items
    $stmtOI = $db->prepare("
        SELECT od.*, p.nama_produk, p.gambar_satu
        FROM order_details od
        JOIN products p ON od.product_id = p.product_id
        WHERE od.order_id = ?
    ");
    $stmtOI->execute([$order_id]);
    $items = $stmtOI->fetchAll(PDO::FETCH_ASSOC);

    $stmtshipments = $db->prepare("SELECT nomor_resi FROM shipments WHERE order_id = ?");
    $stmtshipments->execute([$order_id]);
    $shipments = $stmtshipments->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($shipments);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order - Admin Dashboard</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="../resources/css/detailorderadmin.css">
    <script src="../resources/js/alert-detailorder-admin.js"></script>
</head>

<body>
    <div class="container">
        <?php require "template/sidebar.php"; ?>

        <div class="main-content">
            <?php require "template/header.php"; ?>

            <div id="alert-container"></div>

            <div class="order-detail-container">
                <?php if ($order): ?>
                    <div class="order-header">
                        <h2><a href="../admin/orderlist.php" style="text-decoration: none; color:gray;">
                                < Back</a> | Detail Order #<?= htmlspecialchars($order['order_id']) ?></h2>
                        <span class="order-date">
                            <?= date('d F Y H:i', strtotime($order['created_at'])) ?>
                        </span>
                    </div>

                    <div class="order-info">
                        <div class="info-group">
                            <h3>Informasi Pelanggan</h3>
                            <div class="info-item">
                                <span class="info-label">Username:</span>
                                <span><?= htmlspecialchars($order['nama_lengkap'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nama Penerima:</span>
                                <span><?= htmlspecialchars($order['nama_penerima'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">No. Telepon:</span>
                                <span><?= htmlspecialchars($order['nomor_penerima'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span><?= htmlspecialchars($order['email'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="info-group">
                            <h3>Informasi Pengiriman</h3>
                            <div class="info-item">
                                <span class="info-label">Alamat:</span>
                                <span><?= htmlspecialchars($order['alamat_penerima'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Kota:</span>
                                <span><?= htmlspecialchars($order['kota'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Kode Pos:</span>
                                <span><?= htmlspecialchars($order['kodepos'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?= htmlspecialchars($item['gambar_satu']) ?>" class="product-image"
                                                alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                                            <?= htmlspecialchars($item['nama_produk']) ?>
                                        </div>
                                    </td>
                                    <td>Rp <?= number_format($item['harga_order'], 0, ',', '.') ?></td>
                                    <td><?= $item['jumlah_order'] ?></td>
                                    <td>Rp <?= number_format($item['harga_order'] * $item['jumlah_order'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total Pembayaran:</strong></td>
                                <td><strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex gap-4">
                        <!-- Form Update Status -->
                        <form class="status-form" method="POST"
                            action="<?= $_SERVER['PHP_SELF'] ?>?order_id=<?= $order['order_id'] ?>">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <h3>Update Status Pesanan</h3>
                            <select name="status" class="status-select">
                                <option value="pending" <?= $order['transaction_status'] == 'pending' ? 'selected' : '' ?>>
                                    Pending</option>
                                <option value="processing" <?= $order['transaction_status'] == 'processing' ? 'selected' : '' ?>>Dikemas</option>
                                <option value="shipped" <?= $order['transaction_status'] == 'shipped' ? 'selected' : '' ?>>
                                    Dikirim</option>
                                <option value="delivered" <?= $order['transaction_status'] == 'delivered' ? 'selected' : '' ?>>
                                    Terkirim</option>
                                <option value="cancelled" <?= $order['transaction_status'] == 'cancelled' ? 'selected' : '' ?>>
                                    Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="update-btn">Update Status</button>
                        </form>

                        <!-- Form Input Resi -->
                        <form class="resi-form" method="POST"
                            action="<?= $_SERVER['PHP_SELF'] ?>?order_id=<?= $order['order_id'] ?>">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <h3>Input Nomor Resi</h3>
                            <div class="input-group">
                                <input type="text" name="nomor_resi" class="resi-input" placeholder="Masukkan nomor resi"
                                    value="<?= isset($shipments[0]['nomor_resi']) ? htmlspecialchars($shipments[0]['nomor_resi']) : '' ?>">
                                <button type="submit" name="update_resi" class="resi-btn">Simpan Resi</button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <p>Order tidak ditemukan</p>
                <?php endif; ?>
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
        // Optional: Add confirmation before status update
        document.querySelector('.status-form').addEventListener('submit', function (e) {
            if (!confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>
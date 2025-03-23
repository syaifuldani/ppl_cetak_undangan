<?php
session_start();
require '../config/connection.php'; // Pastikan path sesuai dengan struktur folder Anda
require '../config/function.php'; // Pastikan path sesuai dengan struktur folder Anda

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'admin') {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$jenishalaman = "Order list";

// Sertakan file koneksi ke database

// Tentukan jumlah data per halaman
$limit = 10;

// Ambil halaman saat ini dari URL, jika tidak ada set ke 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_sql = "SELECT COUNT(*) as total FROM orders";
$total_stmt = $GLOBALS["db"]->prepare($total_sql);
$total_stmt->execute();
$total_data = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_data / $limit);

$orders = getOrderList($limit, $offset);

$n = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <div class="main">

            <?php require "template/header.php"; ?>

            <div class="content">
                <h3>Riwayat Penjualan</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Order ID</th>
                            <th>Tanggal</th>
                            <th>Nama Kustomer</th>
                            <th>Status Transaksi</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $n += 1 ?></td>
                                <td>Undangan Pernikahan</td> <!-- Ganti dengan data produk yang sesuai jika ada -->
                                <td>#<?= $order['order_id']; ?></td>
                                <td><?= date('M jS, Y', strtotime($order['created_at'])); ?></td>
                                <td><?= $order['nama_lengkap']; ?></td>
                                <td><span
                                        class="<?= strtolower($order['transaction_status']); ?>"><?= ucfirst($order['transaction_status']); ?></span>
                                </td>
                                <td>Rp.<?= number_format($order['total_harga'], 2); ?></td>
                                <td><a
                                        href="detail_order.php?order_id=<?= htmlspecialchars($order['order_id']); ?>"><button>Rincian</button></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <ul>
                        <?php if ($page > 1): ?>
                            <li><a href="?page=<?= $page - 1; ?>">&lt; Prev</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="<?= ($i == $page) ? 'active' : ''; ?>">
                                <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li><a href="?page=<?= $page + 1; ?>">Next &gt;</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
</body>

</html>
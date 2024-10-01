<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

$jenishalaman = "Order list";

// Sertakan file koneksi ke database
require '../config/connection.php'; // Pastikan path sesuai dengan struktur folder Anda

// Tentukan jumlah data per halaman
$limit = 10;

// Ambil halaman saat ini dari URL, jika tidak ada set ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total entri
$total_sql = "SELECT COUNT(*) as total FROM orders";
$total_stmt = $GLOBALS["db"]->prepare($total_sql);
$total_stmt->execute();
$total_data = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data untuk halaman saat ini
$sql = "SELECT o.order_id, o.tanggal_pemesanan, o.status_pemesanan, o.total_harga, u.nama_lengkap
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        LIMIT :limit OFFSET :offset";
$stmt = $GLOBALS["db"]->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();
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
                            <th></th>
                            <th>Product</th>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Undangan Pernikahan</td> <!-- Ganti dengan data produk yang sesuai jika ada -->
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td><?php echo date('M jS, Y', strtotime($order['tanggal_pemesanan'])); ?></td>
                                <td><?php echo $order['nama_lengkap']; ?></td>
                                <td><span class="<?php echo strtolower($order['status_pemesanan']); ?>"><?php echo ucfirst($order['status_pemesanan']); ?></span></td>
                                <td>Rp.<?php echo number_format($order['total_harga'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <ul>
                        <?php if ($page > 1): ?>
                            <li><a href="?page=<?php echo $page - 1; ?>">&lt; Prev</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li><a href="?page=<?php echo $page + 1; ?>">Next &gt;</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
</body>

</html>

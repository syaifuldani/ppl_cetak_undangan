<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

$jenishalaman = "Produk";

// Sertakan file koneksi ke database
require '../config/connection.php'; // Pastikan path sesuai dengan struktur folder Anda

// Tentukan jumlah data per halaman
$limit = 10;

// Ambil halaman saat ini dari URL, jika tidak ada set ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total entri
$total_sql = "SELECT COUNT(*) as total FROM products";
$total_stmt = $pdo->prepare($total_sql);
$total_stmt->execute();
$total_data = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data untuk halaman saat ini
$sql = "SELECT product_id, nama_produk, harga_product, gambar_satu FROM products LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <main class="main-content">

            <?php require "template/header.php"; ?>

            <div class="add-product-button">
                <button onclick="window.location.href='tambah_barang.php'">Tambah Barang</button>
            </div>

            <div class="content">
                <h3>List Produk</h3>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><img src="data:image/jpeg;base64,<?php echo base64_encode($product['gambar_satu']); ?>" alt="Gambar Produk" style="width: 50px; height: auto;"></td>
                                    <td>#<?php echo $product['product_id']; ?></td>
                                    <td><?php echo $product['nama_produk']; ?></td>
                                    <td>Rp.<?php echo number_format($product['harga_product'], 2); ?></td>
                                    <td>
                                        <div class="aksi">
                                            <button onclick="window.location.href='edit_barang.php?id=<?php echo $product['product_id']; ?>'" class="edit-btn">Edit</button>
                                            <button onclick="if(confirm('Are you sure you want to delete this product?')) window.location.href='hapus_barang.php?id=<?php echo $product['product_id']; ?>'" class="delete-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

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

        </main>
    </div>
</body>

</html>

<?php
session_start();
require '../config/connection.php'; // Menghubungkan dengan file connection.php
require '../config/function.php';   // Jika diperlukan, untuk fungsi tambahan

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$jenishalaman = "Daftar Customer";

// Tentukan jumlah data per halaman
$limit = 10;

// Ambil halaman saat ini dari URL, jika tidak ada set ke 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total data customer
$total_sql = "SELECT COUNT(*) as total FROM users WHERE jenis_pengguna = 'customer'";  // Memfilter dengan jenis_pengguna = 'customer'
$total_stmt = $GLOBALS['db']->prepare($total_sql);
$total_stmt->execute();
$total_data = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_data / $limit);

// Query untuk mengambil data customer
$sql = "SELECT user_id, nama_lengkap, profile_image, email, nomor_telepon, jenis_pengguna FROM users WHERE jenis_pengguna = 'customer' LIMIT :limit OFFSET :offset";
$stmt = $GLOBALS['db']->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$n = 0;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE user_id = :user_id";
    $delete_stmt = $GLOBALS['db']->prepare($delete_sql);
    $delete_stmt->bindParam(':user_id', $delete_id, PDO::PARAM_INT);
    if ($delete_stmt->execute()) {
        header("Location: daftar_customer.php"); // Redirect setelah penghapusan
        exit();
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Customer</title>
    <link rel="stylesheet" href="./style/style.css">
    <script>
        function confirmDelete(url) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = url;
            }
        }
    </script>
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <div class="main">

            <?php require "template/header.php"; ?>

            <div class="content">
                <h3>Daftar Customer</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Profile Image</th>
                            <th>Email</th>
                            <th>Nomor Telepon</th>
                            <th>Aksi</th> <!-- Kolom Aksi untuk tombol Delete -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?= ++$n; ?></td>
                                <td><?= htmlspecialchars($customer['nama_lengkap']); ?></td>
                                <td>
                                    <img src="../images/<?= htmlspecialchars($customer['profile_image']); ?>" alt="Profile Image" width="50" height="50">
                                </td>
                                <td><?= htmlspecialchars($customer['email']); ?></td>
                                <td><?= htmlspecialchars($customer['nomor_telepon']); ?></td>
                                <td>
                                    <button onclick="confirmDelete('?delete_id=<?= $customer['user_id']; ?>')">Delete</button> <!-- Tombol Delete -->
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

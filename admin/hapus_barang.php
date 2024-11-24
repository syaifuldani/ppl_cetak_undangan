<?php
session_start();

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'admin') {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

require '../config/connection.php';

// Alias objek PDO dari $GLOBALS['db'] ke $pdo untuk kompatibilitas
$pdo = $GLOBALS['db'];

// Validasi dan sanitasi ID produk dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int) $_GET['id'];
} else {
    echo "ID produk tidak valid.";
    exit();
}

// Optional: Tambahkan konfirmasi sebelum menghapus (sudah dilakukan di sisi klien melalui konfirmasi JavaScript)

// Hapus data produk dari database
$sql = "DELETE FROM products WHERE product_id = :id";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect dengan pesan sukses
        header("Location: product.php?status=deleted&message=Produk berhasil dihapus");
        exit();
    } else {
        // Jika eksekusi gagal, tampilkan pesan error
        $errorInfo = $stmt->errorInfo();
        echo "Error saat menghapus produk: " . htmlspecialchars($errorInfo[2]);
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit();
}
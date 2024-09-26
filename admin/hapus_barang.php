<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php");
    exit();
}

require '../config/connection.php';

$product_id = $_GET['id'];
$sql = "DELETE FROM products WHERE product_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $product_id);

if ($stmt->execute()) {
    header("Location: product.php?status=deleted");
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}
?>

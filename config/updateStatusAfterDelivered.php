<?php
session_start();
require_once '../config/connection.php';
require_once '../config/function.php';

header('Content-Type: application/json');

try {
    // Validasi input
    if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
        throw new Exception('Data tidak lengkap');
    }

    // Validasi user sudah login
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Silakan login terlebih dahulu');
    }

    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    // Update status menggunakan fungsi yang sudah ada
    $result = updateStatusByOrderId($orderId, $newStatus);

    if ($result['status'] === 'success') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diupdate'
        ]);
    } else {
        throw new Exception($result['message']);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
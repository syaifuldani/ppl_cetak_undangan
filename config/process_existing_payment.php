<?php

session_start();
require_once '../config/connection.php';
require_once '../config/midtrans_config.php';

header('Content-Type: application/json');

try {
    // Validasi session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Anda harus login terlebih dahulu');
    }

    // Ambil dan validasi input
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['order_id'])) {
        // Coba ambil dari POST jika tidak ada di JSON
        $input['order_id'] = $_POST['order_id'] ?? null;
    }

    if (!$input['order_id']) {
        throw new Exception('Order ID tidak ditemukan');
    }

    // Debug log
    error_log("Processing payment for order_id: " . $input['order_id']);
    error_log("User ID: " . $_SESSION['user_id']);

    // Proses order
    $result = process_existing_order($input['order_id'], $_SESSION['user_id']);

    // Karena process_existing_order langsung mengembalikan snap token
    // Return response
    echo json_encode([
        'status' => 'success',
        'snap_token' => $result
    ]);

} catch (Exception $e) {
    error_log("Payment Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
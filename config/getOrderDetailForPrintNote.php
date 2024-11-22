<?php
session_start();
require_once '../config/connection.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    $order_id = $_GET['order_id'] ?? null;
    if (!$order_id) {
        throw new Exception('Order ID is required');
    }

    // Get order details
    $stmt = $db->prepare("
        SELECT o.*, 
               od.jumlah_order, od.harga_order,
               p.nama_produk, p.gambar_satu
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        JOIN products p ON od.product_id = p.product_id
        WHERE o.order_id = ? AND o.user_id = ?
    ");

    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        throw new Exception('Order not found');
    }

    // Format response
    $order = [
        'order_id' => $results[0]['order_id'],
        'created_at' => $results[0]['created_at'],
        'nama_penerima' => $results[0]['nama_penerima'],
        'nomor_penerima' => $results[0]['nomor_penerima'],
        'alamat_penerima' => $results[0]['alamat_penerima'],
        'total_harga' => $results[0]['total_harga'],
        'transaction_status' => $results[0]['transaction_status'],
        'payment_type' => $results[0]['payment_type'],
        'items' => array_map(function ($row) {
            return [
                'nama_produk' => $row['nama_produk'],
                'harga_order' => $row['harga_order'],
                'jumlah_order' => $row['jumlah_order'],
                'gambar_satu' => $row['gambar_satu']
            ];
        }, $results)
    ];

    echo json_encode($order);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
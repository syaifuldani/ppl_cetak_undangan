<?php
ob_start();
session_start();
require_once '../config/connection.php';

if (ob_get_length())
    ob_clean();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    $order_id = $_GET['order_id'] ?? null;
    if (!$order_id) {
        throw new Exception('Order ID is required');
    }

    // Get order and shipment details
    $orderQuery = "SELECT o.*, s.ekspedisi, s.nomor_resi, s.biaya_ongkir, s.estimasi_sampai
                  FROM orders o
                  LEFT JOIN shipments s ON o.order_id = s.order_id
                  WHERE o.order_id = ? AND o.user_id = ?";

    $orderStmt = $db->prepare($orderQuery);
    $orderStmt->execute([$order_id, $_SESSION['user_id']]);
    $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderData) {
        throw new Exception('Order not found');
    }

    // Get order items
    $itemsQuery = "SELECT od.*, p.nama_produk
                  FROM order_details od
                  JOIN products p ON od.product_id = p.product_id
                  WHERE od.order_id = ?";

    $itemsStmt = $db->prepare($itemsQuery);
    $itemsStmt->execute([$order_id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Format response
    $response = [
        'success' => true,
        'order' => [
            'order_id' => $orderData['order_id'],
            'created_at' => $orderData['created_at'],
            'nama_penerima' => $orderData['nama_penerima'],
            'nomor_penerima' => $orderData['nomor_penerima'],
            'alamat_penerima' => $orderData['alamat_penerima'],
            'total_harga' => $orderData['total_harga'],
            'transaction_status' => $orderData['transaction_status'],
            'payment_type' => $orderData['payment_type'],
            'ekspedisi' => $orderData['ekspedisi'],
            'nomor_resi' => $orderData['nomor_resi'],
            'biaya_ongkir' => $orderData['biaya_ongkir'],
            'estimasi_sampai' => $orderData['estimasi_sampai'],
            'items' => array_map(function ($item) {
                return [
                    'nama_produk' => $item['nama_produk'],
                    'harga_order' => $item['harga_order'],
                    'jumlah_order' => $item['jumlah_order'],
                ];
            }, $items)
        ]
    ];

    ob_clean();
    echo json_encode($response);
    exit;

} catch (Exception $e) {
    ob_clean();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
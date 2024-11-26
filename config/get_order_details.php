<?php

// get_order_details.php
session_start();
require_once '../config/connection.php';

header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Silakan login terlebih dahulu');
    }

    $user_id = $_SESSION['user_id'];

    // Check if order_id is provided
    if (!isset($_GET['order_id'])) {
        throw new Exception('ID pesanan tidak ditemukan');
    }

    $order_id = $_GET['order_id'];

    // Debug log
    error_log("Fetching order details - User ID: $user_id, Order ID: $order_id");

    // Get order details
    $sql = "SELECT o.*, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.user_id 
            WHERE o.order_id = :order_id AND o.user_id = :user_id";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':order_id' => $order_id,
        ':user_id' => $user_id
    ]);

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception('Pesanan tidak ditemukan');
    }

    // Get order details with shipment data
    $sqlshipments = "SELECT o.order_id, u.email, s.ekspedisi, s.nomor_resi, s.biaya_ongkir, s.estimasi_sampai
            FROM orders o 
            JOIN users u ON o.user_id = u.user_id 
            LEFT JOIN shipments s ON o.order_id = s.order_id
            WHERE o.order_id = :order_id AND o.user_id = :user_id";

    $statement = $db->prepare($sqlshipments);
    $statement->execute([
        ':order_id' => $order_id,
        ':user_id' => $user_id
    ]);

    $shipments = $statement->fetchAll(PDO::FETCH_ASSOC);

    $order['shipments'] = $shipments;

    // Get order items
    $sql = "SELECT od.*, p.nama_produk, p.gambar_satu 
            FROM order_details od
            JOIN products p ON od.product_id = p.product_id
            WHERE od.order_id = :order_id";

    $stmt = $db->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add items to order data
    $order['items'] = $items;

    // Debug log
    error_log("Order details found: " . json_encode($order));

    echo json_encode([
        'success' => true,
        'order' => $order
    ]);

} catch (Exception $e) {
    error_log("Error in get_order_details.php: " . $e->getMessage());

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
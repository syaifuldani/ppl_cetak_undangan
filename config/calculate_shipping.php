<?php
session_start();
require_once '../config/connection.php';
require_once '../config/rajaOngkir.php';
require_once '../config/WeightCalculator.php'; // Tambahkan ini

header('Content-Type: application/json');

try {
    // Debug incoming data
    error_log("POST Data: " . print_r($_POST, true));

    // Validasi input
    if (!isset($_POST['kota']) || !isset($_POST['courier'])) {
        throw new Exception('Incomplete data');
    }

    // Validasi session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User tidak terautentikasi');
    }

    // Ambil items dari cart
    $sql = "SELECT c.jumlah as quantity, p.paper_type, p.paper_size 
            FROM carts c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.user_id = :user_id";

    $stmt = $db->prepare($sql);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        throw new Exception('Keranjang belanja kosong');
    }

    // Debug cart items
    error_log("Cart Items: " . print_r($cartItems, true));

    // Hitung total berat
    $totalWeight = 0;
    foreach ($cartItems as $item) {
        $itemWeight = WeightCalculator::calculateWeight(
            $item['paper_type'],
            $item['paper_size'],
            $item['quantity']
        );
        error_log("Item weight: $itemWeight g");
        $totalWeight += $itemWeight;
    }

    // Debug weight
    error_log("Total Weight: " . $totalWeight);

    // Inisialisasi RajaOngkir
    $rajaOngkir = new RajaOngkir();

    // Siapkan parameter untuk calculateShipping
    $params = [
        'destination' => $_POST['kota'],
        'weight' => $totalWeight,
        'courier' => strtolower($_POST['courier'])
    ];

    // Debug shipping parameters
    error_log("Shipping Parameters: " . print_r($params, true));

    // Hitung ongkir
    $result = $rajaOngkir->calculateShipping(
        $params['destination'],
        $params['courier'],
        $params['weight']
    );

    // Debug result
    error_log("RajaOngkir Result: " . print_r($result, true));

    // Tambahkan informasi tambahan ke response
    $response = [
        'status' => 'success',
        'weight_details' => [
            'total_weight' => $totalWeight,
        ],
        $result
    ];

    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error in calculate_shipping.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
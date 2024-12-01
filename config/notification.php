<?php

// Buat file baru: notification.php
require_once __DIR__ . '/midtrans_config.php';
require_once __DIR__ . '/connection.php';

use Dotenv\Dotenv;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

$dotenv = Dotenv::createImmutable(__DIR__ . '');
$dotenv->load();

Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

// Log raw request
$raw_post = file_get_contents('php://input');
error_log("Raw POST data: " . $raw_post);
error_log("POST array: " . print_r($_POST, true));

try {
    $notif = new Midtrans\Notification();
    $transaction = $notif->transaction_status;
    $order_id = $notif->order_id;

    error_log("Notification object: " . print_r($notif, true));

    $transaction = $notif->transaction_status;
    $type = $notif->payment_type;
    $fraud = $notif->fraud_status;

    // Cek apakah ini order dengan suffix
    $original_order_id = $order_id;
    if (strpos($order_id, '-') !== false) {
        $original_order_id = explode('-', $order_id)[0];
    }

    error_log("Processing payment notification: " . json_encode([
        'original_order_id' => $original_order_id,
        'received_order_id' => $order_id,
        'transaction_status' => $transaction,
        'payment_type' => $type,
        'fraud_status' => $fraud
    ]));

    // Update database untuk order asli
    $sql = "UPDATE orders SET 
            payment_type = :payment_type,
            transaction_status = :transaction_status,
            transaction_time = :transaction_time,
            payment_details = :payment_details,
            fraud_status = :fraud_status
            WHERE order_id = :order_id";

    $stmt = $GLOBALS['db']->prepare($sql);

    $paymentDetails = json_encode([
        'transaction_id' => $notif->transaction_id,
        'status_code' => $notif->status_code,
        'status_message' => $notif->status_message,
        'gross_amount' => $notif->gross_amount,
        'original_order_id' => $original_order_id,
        'payment_order_id' => $order_id
    ]);

    $params = [
        ':payment_type' => $type,
        ':transaction_status' => $transaction,
        ':transaction_time' => date('Y-m-d H:i:s'),
        ':payment_details' => $paymentDetails,
        ':fraud_status' => $fraud,
        ':order_id' => $original_order_id  // Gunakan original order ID
    ];

    error_log("Update parameters: " . json_encode($params));

    $result = $stmt->execute($params);

    if ($result) {
        // Verifikasi update
        $verifyStmt = $GLOBALS['db']->prepare("SELECT * FROM orders WHERE order_id = ?");
        $verifyStmt->execute([$original_order_id]);
        $updatedOrder = $verifyStmt->fetch(PDO::FETCH_ASSOC);
        error_log("Updated order data: " . json_encode($updatedOrder));

        echo json_encode([
            'status' => 'success',
            'message' => 'Payment notification processed successfully'
        ]);
    } else {
        throw new Exception("Failed to update order status");
    }

} catch (Exception $e) {
    error_log("Notification Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
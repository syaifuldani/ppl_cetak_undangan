<?php
require_once __DIR__ . '../../config/midtrans_config.php';
require_once __DIR__ . '../../config/connection.php';

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

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$raw_post = file_get_contents('php://input');
error_log("Raw POST data: " . $raw_post);

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/midtrans_notification.log');

try {
    $notif = new Notification();
    error_log("Notification object created: " . json_encode($notif));

    $transaction_status = $notif->transaction_status;
    $order_id = $notif->order_id;
    $payment_type = $notif->payment_type;
    $fraud_status = $notif->fraud_status;

    // Extract base order_id
    $base_order_id = preg_replace('/-[a-f0-9]+$/', '', $order_id);
    error_log("Processing order ID: $base_order_id");

    $GLOBALS['db']->beginTransaction();

    try {
        // Update untuk semua tipe status, tidak hanya settlement
        $sql = "UPDATE orders SET 
                payment_type = :payment_type,
                transaction_status = :transaction_status,
                transaction_time = NOW(),
                payment_details = :payment_details,
                fraud_status = :fraud_status,
                updated_at = NOW()
                WHERE order_id = :order_id";

        $paymentDetails = json_encode([
            'transaction_id' => $notif->transaction_id,
            'payment_type' => $payment_type,
            'transaction_time' => date('Y-m-d H:i:s'),
            'transaction_status' => $transaction_status,
            'gross_amount' => $notif->gross_amount,
            'fraud_status' => $fraud_status,
            'status_code' => $notif->status_code,
            'status_message' => $notif->status_message,
            'original_order_id' => $order_id
        ]);

        $stmt = $GLOBALS['db']->prepare($sql);
        $params = [
            ':payment_type' => $payment_type,
            ':transaction_status' => $transaction_status,
            ':payment_details' => $paymentDetails,
            ':fraud_status' => $fraud_status,
            ':order_id' => $base_order_id
        ];

        error_log("Executing update with params: " . json_encode($params));

        if (!$stmt->execute($params)) {
            throw new Exception("Failed to update order: " . json_encode($stmt->errorInfo()));
        }

        $GLOBALS['db']->commit();

        // Kirim response 200 OK ke Midtrans
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Notification processed successfully'
        ]);
        exit;

    } catch (Exception $e) {
        $GLOBALS['db']->rollBack();
        error_log("Database error: " . $e->getMessage());
        throw $e;
    }

} catch (Exception $e) {
    error_log("Notification Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    // Tetap kirim 200 OK ke Midtrans untuk menghentikan retry
    http_response_code(200);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
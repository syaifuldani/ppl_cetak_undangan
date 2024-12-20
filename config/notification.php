<?php
require_once __DIR__ . '../../config/midtrans_config.php';
require_once __DIR__ . '../../config/connection.php';
use Midtrans\Notification;

try {
    $notif = new Notification();
    error_log("Received notification: " . json_encode($notif));

    $transaction_status = $notif->transaction_status;
    $order_id = $notif->order_id;
    $payment_type = $notif->payment_type;
    $fraud_status = $notif->fraud_status;

    // Extract base order_id (remove any suffix)
    $base_order_id = preg_replace('/-[a-f0-9]+$/', '', $order_id);

    $db->beginTransaction();

    try {
        // Selalu update ke status terbaru jika settlement
        if ($transaction_status === 'settlement') {
            $sql = "UPDATE orders SET 
                    payment_type = :payment_type,
                    transaction_status = :transaction_status,
                    transaction_time = :transaction_time,
                    payment_details = :payment_details,
                    fraud_status = :fraud_status,
                    updated_at = NOW()
                    WHERE order_id = :order_id";

            $paymentDetails = json_encode([
                'transaction_id' => $notif->transaction_id,
                'payment_type' => $payment_type,
                'transaction_time' => $notif->transaction_time,
                'transaction_status' => $transaction_status,
                'gross_amount' => $notif->gross_amount,
                'fraud_status' => $fraud_status,
                'status_code' => $notif->status_code,
                'status_message' => $notif->status_message,
                'original_order_id' => $order_id
            ]);

            $stmt = $db->prepare($sql);
            $params = [
                ':payment_type' => $payment_type,
                ':transaction_status' => $transaction_status,
                ':transaction_time' => $notif->transaction_time,
                ':payment_details' => $paymentDetails,
                ':fraud_status' => $fraud_status,
                ':order_id' => $base_order_id
            ];

            if (!$stmt->execute($params)) {
                throw new Exception("Failed to update order status");
            }

            // Verify the update
            $verify_sql = "SELECT transaction_status FROM orders WHERE order_id = ?";
            $verify_stmt = $db->prepare($verify_sql);
            $verify_stmt->execute([$base_order_id]);
            $updated_status = $verify_stmt->fetchColumn();

            if ($updated_status !== 'settlement') {
                throw new Exception("Failed to update to settlement status");
            }
        }

        $db->commit();

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Notification processed successfully'
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    error_log("Error processing notification: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
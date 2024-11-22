<?php
require_once __DIR__ . '/../vendor/midtrans/midtrans-php/Midtrans.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentHandler
{
    private $db;
    private $user_id;

    public function __construct($db, $user_id)
    {
        $this->db = $db;
        $this->user_id = $user_id;
        $this->initializeMidtrans();
    }

    private function initializeMidtrans()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '');
        $dotenv->load();

        Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function processPayment($data)
    {
        try {
            // Validate input data
            $this->validateInputData($data);

            // Get cart items
            $cart_items = $this->getCartItems();
            if (empty($cart_items)) {
                throw new Exception("Keranjang belanja kosong");
            }

            // Calculate total amount
            $total_amount = $this->calculateTotalAmount();

            // Generate order data
            $order_id = $this->generateOrderId();

            $data['notelppenerima'] = $this->formatPhoneNumber($data['notelppenerima']);
            $transaction_data = $this->prepareTransactionData($data, $cart_items, $order_id, $total_amount);

            // Get Snap Token
            $snap_token = Snap::getSnapToken($transaction_data);

            // Save order to database
            $this->saveOrder($order_id, $data, $total_amount, $cart_items);

            return [
                'status' => 'success',
                'snap_token' => $snap_token,
                'order_id' => $order_id
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function validateInputData($data)
    {
        $required_fields = ['namapenerima', 'notelppenerima', 'alamatpenerima'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field $field wajib diisi");
            }
        }
    }

    private function getCartItems()
    {
        $sql = "SELECT c.cart_id, p.nama_produk, c.jumlah, p.harga_produk, c.product_id, c.total_harga 
                FROM carts c 
                JOIN products p ON c.product_id = p.product_id 
                WHERE c.user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function calculateTotalAmount()
    {
        $sql = "SELECT SUM(total_harga) as total 
                FROM carts 
                WHERE user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float) $result['total'];
    }

    private function generateOrderId()
    {
        return time() . '-' . bin2hex(random_bytes(8));
    }

    private function prepareTransactionData($data, $cart_items, $order_id, $total_amount)
    {
        $item_details = array_map(function ($item) {
            return [
                'id' => $item['product_id'],
                'price' => (int) $item['harga_produk'],
                'quantity' => (int) $item['jumlah'],
                'name' => $item['nama_produk']
            ];
        }, $cart_items);

        return [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int) $total_amount
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => htmlspecialchars($data['namapenerima']),
                'email' => $data['email'] ?? '',
                'phone' => htmlspecialchars($data['notelppenerima']),
                'billing_address' => [
                    'first_name' => htmlspecialchars($data['namapenerima']),
                    'phone' => htmlspecialchars($data['notelppenerima']),
                    'address' => htmlspecialchars($data['alamatpenerima']),
                    'city' => htmlspecialchars($data['kota']),
                    'postal_code' => htmlspecialchars($data['kodepos']),
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'first_name' => htmlspecialchars($data['namapenerima']),
                    'phone' => htmlspecialchars($data['notelppenerima']),
                    'address' => htmlspecialchars($data['alamatpenerima']),
                    'city' => htmlspecialchars($data['kota']),
                    'postal_code' => htmlspecialchars($data['kodepos']),
                    'country_code' => 'IDN'
                ]
            ]
        ];
    }

    // Jika Anda perlu memisahkan nama lengkap menjadi first_name dan last_name
    private function splitName($fullName)
    {
        $parts = explode(' ', trim($fullName));
        $lastName = count($parts) > 1 ? array_pop($parts) : '';
        $firstName = implode(' ', $parts);

        return [
            'first_name' => $firstName ?: $fullName,
            'last_name' => $lastName
        ];
    }

    private function formatPhoneNumber($phone)
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika nomor dimulai dengan 0, ganti dengan +62
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        }

        // Jika nomor belum memiliki kode negara, tambahkan +62
        if (substr($phone, 0, 2) !== '62' && substr($phone, 0, 3) !== '+62') {
            $phone = '+62' . $phone;
        }

        // Jika nomor dimulai dengan 62 tanpa +, tambahkan +
        if (substr($phone, 0, 2) === '62') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    private function saveOrder($order_id, $data, $total_amount, $cart_items)
    {
        try {
            $this->db->beginTransaction();

            // 1. Simpan order terlebih dahulu
            $sql = "INSERT INTO orders (order_id, user_id, total_harga, nama_penerima, nomor_penerima, alamat_penerima, transaction_status, keterangan_order) 
                    VALUES (:order_id, :user_id, :total_harga, :nama_penerima, :nomor_penerima, :alamat_penerima, 'pending',:keterangan_order)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':order_id' => $order_id,
                ':user_id' => $this->user_id,
                ':total_harga' => $total_amount,
                ':nama_penerima' => $data['namapenerima'],
                ':nomor_penerima' => $data['notelppenerima'],
                ':alamat_penerima' => $data['alamatpenerima'],
                ':keterangan_order' => $data['keterangan_order']
            ]);

            // 2. Pastikan order tersimpan
            if ($stmt->rowCount() === 0) {
                throw new Exception("Gagal menyimpan order");
            }

            // 3. Simpan order details
            $sql = "INSERT INTO order_details (order_id, product_id, jumlah_order, harga_order) 
                    VALUES (:order_id, :product_id, :jumlah_order, :harga_order)";

            $stmt = $this->db->prepare($sql);
            foreach ($cart_items as $item) {
                $result = $stmt->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $item['product_id'],
                    ':jumlah_order' => $item['jumlah'],
                    ':harga_order' => $item['harga_produk'],

                ]);

                if (!$result) {
                    throw new Exception("Gagal menyimpan detail order");
                }
            }

            // 4. Hapus items dari cart setelah order berhasil
            $sql = "DELETE FROM carts WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $this->user_id]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Gagal menyimpan order: ' . $e->getMessage());
        }
    }

    public function handleNotification()
    {
        try {
            $notif = new Notification();

            // Log raw notification untuk debugging
            error_log("Raw notification received: " . json_encode($_POST));

            $transaction_status = $notif->transaction_status;
            $payment_type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud_status = $notif->fraud_status;

            // Log notification object
            error_log("Notification object: " . json_encode([
                'order_id' => $order_id,
                'transaction_status' => $transaction_status,
                'payment_type' => $payment_type,
                'fraud_status' => $fraud_status,
                'transaction_time' => $notif->transaction_time,
                'gross_amount' => $notif->gross_amount
            ]));

            $order_status = $this->determineOrderStatus(
                $transaction_status,
                $payment_type,
                $fraud_status
            );

            // Pass the whole notification object
            $this->updateOrderStatus($order_id, $order_status, $notif);

            return [
                'status' => 'success',
                'message' => "Order status updated to $order_status"
            ];

        } catch (Exception $e) {
            error_log("Notification Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function determineOrderStatus($transaction_status, $payment_type, $fraud_status)
    {
        switch ($transaction_status) {
            case 'capture':
                return ($payment_type == 'credit_card' && $fraud_status == 'challenge')
                    ? 'challenge'
                    : 'success';
            case 'settlement':
                return 'paid';
            case 'pending':
                return 'pending';
            case 'deny':
            case 'expire':
            case 'cancel':
                return $transaction_status;
            default:
                return 'unknown';
        }
    }

    private function updateOrderStatus($order_id, $status, $notif)
    {
        try {
            $sql = "UPDATE orders SET 
                    status = :status,
                    payment_type = :payment_type,
                    transaction_status = :transaction_status,
                    transaction_time = :transaction_time,
                    payment_details = :payment_details,
                    fraud_status = :fraud_status
                    WHERE order_id = :order_id";

            // Convert notification object to JSON for storage
            $paymentDetails = json_encode([
                'transaction_id' => $notif->transaction_id,
                'payment_type' => $notif->payment_type,
                'transaction_time' => $notif->transaction_time,
                'transaction_status' => $notif->transaction_status,
                'gross_amount' => $notif->gross_amount,
                'fraud_status' => $notif->fraud_status,
                'status_code' => $notif->status_code,
                'status_message' => $notif->status_message
            ]);

            $params = [
                ':status' => $status,
                ':payment_type' => $notif->payment_type,
                ':transaction_status' => $notif->transaction_status,
                ':transaction_time' => $notif->transaction_time,
                ':payment_details' => $paymentDetails,
                ':fraud_status' => $notif->fraud_status,
                ':order_id' => $order_id
            ];

            // Log parameters being used in update
            error_log("Updating order with parameters: " . json_encode($params));

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                error_log("SQL Error: " . json_encode($stmt->errorInfo()));
                throw new Exception("Failed to update order status");
            }

            // Verify the update
            $sql = "SELECT * FROM orders WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id]);
            $updatedOrder = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("Order after update: " . json_encode($updatedOrder));

            return true;
        } catch (Exception $e) {
            error_log("Error updating order: " . $e->getMessage());
            throw $e;
        }
    }

    public function processExistingOrder($order_id)
    {
        try {
            // Get order data
            $sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id, $this->user_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception("Order tidak ditemukan");
            }

            // Get order details
            $sql = "SELECT od.*, p.nama_produk 
                FROM order_details od 
                JOIN products p ON od.product_id = p.product_id 
                WHERE od.order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($items)) {
                throw new Exception("Detail order tidak ditemukan");
            }

            // Prepare transaction data
            $transaction_data = [
                'transaction_details' => [
                    'order_id' => $order['order_id'],
                    'gross_amount' => (int) $order['total_harga']
                ],
                'item_details' => array_map(function ($item) {
                    return [
                        'id' => $item['product_id'],
                        'price' => (int) $item['harga_order'],
                        'quantity' => (int) $item['jumlah_order'],
                        'name' => $item['nama_produk']
                    ];
                }, $items),
                'customer_details' => [
                    'first_name' => $order['nama_penerima'],
                    'phone' => $order['nomor_penerima'],
                    'address' => $order['alamat_penerima']
                ]
            ];

            // Get Snap Token
            $snap_token = Snap::getSnapToken($transaction_data);

            return [
                'status' => 'success',
                'snap_token' => $snap_token,
                'order_id' => $order_id
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

// Usage example:

function payment_handled($data, $user_id)
{
    try {
        $paymentHandler = new PaymentHandler($GLOBALS['db'], $user_id);
        $result = $paymentHandler->processPayment($data);

        if ($result['status'] === 'success') {
            return $result['snap_token'];
        } else {
            throw new Exception($result['message']);
        }

    } catch (Exception $e) {
        http_response_code(400);
        throw new Exception($e->getMessage());
    }
}

// Fungsi untuk handle notifikasi
function handle_notification()
{
    try {
        $paymentHandler = new PaymentHandler($GLOBALS['db'], null);
        return $paymentHandler->handleNotification();
    } catch (Exception $e) {
        error_log("Notification Error: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function process_existing_order($order_id, $user_id)
{
    try {
        $paymentHandler = new PaymentHandler($GLOBALS['db'], $user_id);
        $result = $paymentHandler->processExistingOrder($order_id);

        if ($result['status'] === 'success') {
            return $result['snap_token'];
        } else {
            throw new Exception($result['message']);
        }

    } catch (Exception $e) {
        http_response_code(400);
        throw new Exception($e->getMessage());
    }
}
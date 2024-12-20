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
            $transaction_data = $this->prepareTransactionData($data, $cart_items, $order_id, total_amount: $total_amount);

            // Get Snap Token
            $snap_token = Snap::getSnapToken($transaction_data);

            // Simpan data sementara di session
            $_SESSION['pending_order'] = [
                'order_id' => $order_id,
                'data' => $data,
                'total_amount' => $total_amount,
                'cart_items' => $cart_items
            ];

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
        // Get shipping cost information
        $shipping_cost = isset($data['shipping_cost']) ? floatval($data['shipping_cost']) : 0;

        // Calculate final total
        $final_total = $total_amount + $shipping_cost;

        $item_details = array_map(function ($item) {
            return [
                'id' => $item['product_id'],
                'price' => (int) $item['harga_produk'],
                'quantity' => (int) $item['jumlah'],
                'name' => $item['nama_produk']
            ];
        }, $cart_items);

        // Tambahkan biaya pengiriman sebagai item
        if ($shipping_cost > 0) {
            $item_details[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shipping_cost,
                'quantity' => 1,
                'name' => $data['shipping_courier'] . ' Shipping Cost'
            ];
        }

        return [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int) $final_total
            ],
            'enabled_payments' => [
                'credit_card',
                'bank_transfer',
                'gopay',
                'shopeepay',
                'qris',
                'dana',
                'ovo'
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
            // Debug log awal
            error_log("Starting processExistingOrder for order_id: $order_id and user_id: {$this->user_id}");

            // Validasi input
            if (!$order_id) {
                throw new Exception("Order ID is required");
            }

            // Cek status order
            $check_status = "SELECT transaction_status FROM orders WHERE order_id = ?";
            $stmt = $this->db->prepare($check_status);
            $stmt->execute([$order_id]);
            $current_status = $stmt->fetchColumn();

            // Get order data dengan shipment dalam satu query
            $sql = "SELECT o.*, s.biaya_ongkir, s.ekspedisi 
                FROM orders o 
                LEFT JOIN shipments s ON o.order_id = s.order_id 
                WHERE o.order_id = ? AND o.user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id, $this->user_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug order data
            error_log("Order data found: " . json_encode($order));

            if (!$order) {
                throw new Exception("Order tidak ditemukan");
            }

            // Get order details
            $sql = "SELECT od.*, p.nama_produk, p.paper_type, p.paper_size
                FROM order_details od 
                JOIN products p ON od.product_id = p.product_id 
                WHERE od.order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($items)) {
                throw new Exception("Detail order tidak ditemukan");
            }

            // Hitung total berat dari semua item
            $totalWeight = 0;
            foreach ($items as $item) {
                try {
                    $itemWeight = WeightCalculator::calculateWeight(
                        $item['paper_type'],
                        $item['paper_size'],
                        $item['jumlah_order']
                    );
                    $totalWeight += $itemWeight;
                } catch (Exception $e) {
                    error_log("Error calculating weight: " . $e->getMessage());
                    // Default weight jika kalkulasi gagal
                    $totalWeight += 100 * $item['jumlah_order'];
                }
            }

            // Debug items
            error_log("Order items found: " . json_encode($items));

            // Kalkulasi total dengan ongkir
            $total_amount = floatval($order['total_harga']);
            $shipping_cost = floatval($order['biaya_ongkir'] ?? 0);
            $final_total = $total_amount + $shipping_cost;

            // Debug totals
            error_log("Calculations: total_amount = {$total_amount}, shipping_cost = {$shipping_cost}, final_total = {$final_total}");

            // Prepare item details
            $item_details = array_map(function ($item) {
                return [
                    'id' => $item['product_id'],
                    'price' => (int) $item['harga_order'],
                    'quantity' => (int) $item['jumlah_order'],
                    'name' => $item['nama_produk']
                ];
            }, $items);

            // Add shipping cost as separate item if exists
            if ($shipping_cost > 0) {
                $item_details[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $shipping_cost,
                    'quantity' => 1,
                    'name' => ($order['ekspedisi'] ? $order['ekspedisi'] . ' Shipping' : 'Shipping Cost')
                ];
            }

            // Prepare transaction data
            $transaction_data = [
                'transaction_details' => [
                    'order_id' => $order['order_id'],
                    'gross_amount' => (int) $final_total
                ],
                'enabled_payments' => [
                    'credit_card',
                    'bank_transfer',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'dana',
                    'ovo'
                ],
                'item_details' => $item_details,
                'customer_details' => [
                    'first_name' => $order['nama_penerima'],
                    // 'email' => $data['email'] ?? '',
                    'phone' => $order['nomor_penerima'],
                    'billing_address' => [
                        'first_name' => $order['nama_penerima'],
                        'phone' => $order['nomor_penerima'],
                        'address' => $order['alamat_penerima'],
                        'city' => $order['kota'],
                        'postal_code' => $order['kodepos'],
                        'country_code' => 'IDN'
                    ],
                    'shipping_address' => [
                        'first_name' => $order['nama_penerima'],
                        'phone' => $order['nomor_penerima'],
                        'address' => $order['alamat_penerima'],
                        'city' => $order['kota'],
                        'postal_code' => $order['kodepos'],
                        'country_code' => 'IDN'
                    ]
                ]
            ];

            // Debug final transaction data
            error_log("Final transaction data: " . json_encode($transaction_data));

            // Get Snap Token
            $snap_token = Snap::getSnapToken($transaction_data);

            return [
                'status' => 'success',
                'snap_token' => $snap_token,
            ];

        } catch (Exception $e) {
            error_log("Error processing existing order: " . $e->getMessage());
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
        // Debug log
        error_log("Entering process_existing_order with order_id: $order_id, user_id: $user_id");

        $paymentHandler = new PaymentHandler($GLOBALS['db'], $user_id);
        $result = $paymentHandler->processExistingOrder($order_id);

        // Debug log
        error_log("Result from processExistingOrder: " . print_r($result, true));

        if ($result['status'] === 'success') {
            return $result['snap_token'];
        } else {
            throw new Exception($result['message'] ?? 'Unknown error occurred');
        }

    } catch (Exception $e) {
        error_log("Error in process_existing_order: " . $e->getMessage());
        throw new Exception($e->getMessage());
    }
}

class WeightCalculator
{
    // Konstanta untuk gram per m² berbagai jenis kertas
    private static $PAPER_WEIGHTS = [
        'artpaper_120' => 120,
        'artpaper_150' => 150,
        'hvs_70' => 70,
        'hvs_80' => 80
        // Tambahkan jenis kertas lainnya
    ];

    // Konstanta untuk ukuran kertas (dalam cm)
    private static $PAPER_SIZES = [
        'a4' => ['width' => 21.0, 'height' => 29.7],
        'a5' => ['width' => 14.8, 'height' => 21.0],
        'f4' => ['width' => 21.5, 'height' => 33.0]
        // Tambahkan ukuran lainnya
    ];

    public static function calculateWeight($paperType, $paperSize, $quantity)
    {
        // Validasi input
        if (!isset(self::$PAPER_WEIGHTS[$paperType]) || !isset(self::$PAPER_SIZES[$paperSize])) {
            throw new Exception("Invalid paper type or size");
        }

        // Ambil spesifikasi kertas
        $gramPerM2 = self::$PAPER_WEIGHTS[$paperType];
        $dimensions = self::$PAPER_SIZES[$paperSize];

        // Hitung berat per lembar (dalam gram)
        $weightPerSheet = ($dimensions['width'] * $dimensions['height'] * $gramPerM2) / 10000;

        // Hitung total berat
        $totalWeight = $weightPerSheet * $quantity;

        // Bulatkan ke atas ke kelipatan 100 gram terdekat untuk keamanan
        return ceil($totalWeight / 100) * 100;
    }
}
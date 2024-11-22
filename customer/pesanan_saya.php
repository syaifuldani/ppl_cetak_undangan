<?php

// Di PHP, sebelum memulai session
session_start([
    'cookie_secure' => true,
    'cookie_samesite' => 'Lax'
]);

// Security Headers
function setSecurityHeaders()
{
    // Protect against clickjacking
    header("X-Frame-Options: SAMEORIGIN");

    // Protect against XSS and other injections
    // Update Content Security Policy untuk mengizinkan cdnjs
    header("Content-Security-Policy: default-src 'self' https://*.midtrans.com; script-src 'self' https://*.midtrans.com https://cdnjs.cloudflare.com 'unsafe-inline' 'unsafe-eval';  style-src 'self' 'unsafe-inline';  img-src 'self' data: https:;  frame-src https://*.midtrans.com");

    // Prevent MIME-type sniffing
    header("X-Content-Type-Options: nosniff");

    // Enable XSS protection
    header("X-XSS-Protection: 1; mode=block");

    // Use HTTPS only
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

    // Prevent browsers from sending referrer information
    header("Referrer-Policy: same-origin");
}

// Gunakan function di atas
setSecurityHeaders();

require_once '../config/function.php';
require_once '../config/midtrans_config.php';

// Memastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id'];
$orders = getOrdersByID($userId);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link rel="icon" href="../resources/img/icons/pleart.png" type="image/png">
    <!-- // Cetak Note Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="../resources/css/cart.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/pesanan_saya.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key=<?php $_ENV['MIDTRANS_CLIENT_KEY'] ?> crossorigin="anonymous" importance="high" async></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
</head>

<body>

    <div class="container">

        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <div class="pesanan-container">
            <h2>Pesanan Saya</h2>

            <!-- Tab Navigation -->
            <div class="status-tabs-container">
                <nav class="status-tabs" role="tablist">
                    <button class="tab-button" data-status="pending" role="tab">
                        Perlu Dibayar
                    </button>
                    <button class="tab-button" data-status="settlement" role="tab">
                        Dibayar
                    </button>
                    <button class="tab-button" data-status="processing" role="tab">
                        Dikemas
                    </button>
                    <button class="tab-button" data-status="shipped" role="tab">
                        Dikirim
                    </button>
                    <button class="tab-button" data-status="completed" role="tab">
                        Selesai
                    </button>
                    <button class="tab-button" data-status="cancelled" role="tab">
                        Dibatalkan
                    </button>
                </nav>
            </div>

            <!-- Orders Container -->
            <div class="orders-container">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card" data-status="<?= $order['transaction_status'] ?>">
                        <div class="order-header">
                            <div class="order-date">
                                <i class="fas fa-calendar"></i>
                                <?= date('d F Y', strtotime($order['created_at'])) ?>
                            </div>
                            <div class="order-status <?= strtolower($order['transaction_status']) ?>">
                                <span
                                    class="order-status status-<?= !empty($order['transaction_status']) ? strtolower($order['transaction_status']) : 'pending' ?>">
                                    <?= getStatusLabel($order['transaction_status']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-body">
                            <div class="order-items">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="item">
                                        <img src="<?= $item['gambar_satu'] ?>" alt="<?= $item['nama_produk'] ?>"
                                            class="item-image">
                                        <div class="item-details">
                                            <h4><?= $item['nama_produk'] ?></h4>
                                            <p><?= $item['jumlah_order'] ?> x Rp
                                                <?= number_format($item['harga_order'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="order-info">
                                <div class="total-items">
                                    <?= count($order['items']) ?> Produk
                                </div>
                                <div class="total-price">
                                    Total Pesanan: <span>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="order-footer">
                            <?php if ($order['transaction_status'] == 'pending'): ?>
                                <button class="btn-pay" onclick="payOrder('<?= htmlspecialchars($order['order_id']) ?>')"
                                    data-order-id="<?= htmlspecialchars($order['order_id']) ?>" type="button">
                                    Bayar Sekarang
                                </button>
                            <?php elseif ($order['transaction_status'] == 'shipped'): ?>
                                <button class="btn-receive" onclick="confirmReceived('<?= $order['order_id'] ?>')">Pesanan
                                    Diterima</button>
                            <?php endif; ?>
                            <button class="btn-details" onclick="viewOrderDetails('<?= $order['order_id'] ?>')">Lihat
                                Detail</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Tambahkan di bagian akhir file sebelum closing body tag -->
        <div id="orderDetailModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <div id="orderDetailContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all tab buttons and order cards
            const tabButtons = document.querySelectorAll('.tab-button');
            const orderCards = document.querySelectorAll('.order-card');
            const defaultStatus = 'pending'; // Set default status to pending

            // Function to filter orders
            function filterOrders(status) {
                orderCards.forEach(card => {
                    card.style.display = card.dataset.status === status ? 'block' : 'none';
                });

                // Update active tab styling
                tabButtons.forEach(button => {
                    button.classList.toggle('active', button.dataset.status === status);
                });

                // Optional: Update URL with status parameter
                updateURL(status);
            }

            // Function to update URL with status parameter
            function updateURL(status) {
                const url = new URL(window.location);
                url.searchParams.set('status', status);
                window.history.pushState({}, '', url);
            }

            // Add click event to tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterOrders(button.dataset.status);
                });
            });

            // Check URL parameters for status or use default
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');

            // Initialize with status from URL or default to pending
            const initialStatus = statusParam || defaultStatus;

            // Find and activate the corresponding tab
            const activeTab = document.querySelector(`.tab-button[data-status="${initialStatus}"]`);
            if (activeTab) {
                // Apply initial filtering
                filterOrders(initialStatus);
            } else {
                // Fallback to pending if invalid status in URL
                filterOrders(defaultStatus);
            }

            window.confirmReceived = function (orderId) {
                // Implement order received confirmation
                // console.log('Confirm received for order:', orderId);
            }

            window.viewOrderDetails = async function (orderId) {
                const loadingHtml = `
        <div class="loading-spinner" style="text-align: center; padding: 20px;">
            <div class="spinner"></div>
            <p>Memuat detail pesanan...</p>
        </div>
    `;

                const modal = document.getElementById('orderDetailModal');
                const content = document.getElementById('orderDetailContent');

                // Show modal with loading state
                content.innerHTML = loadingHtml;
                modal.style.display = "block";

                try {
                    // console.log('Fetching order details for:', orderId);
                    const response = await fetch(`../config/get_order_details.php?order_id=${orderId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Tandai sebagai AJAX request
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    // console.log('Data received:', data);

                    if (!data.success) {
                        throw new Error(data.message || 'Failed to fetch order details');
                    }

                    const order = data.order;

                    // Format order details HTML
                    content.innerHTML = `
            <div class="order-detail-header">
                <h2>Detail Pesanan</h2>
                <span class="order-status status-${order.transaction_status?.toLowerCase() || 'pending'}">${getStatusLabel(order.transaction_status)}</span>
            </div>

            <div class="detail-section">
                <h3>Informasi Pesanan</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Order ID</div>
                        <div class="detail-value">${order.order_id}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Pesanan</div>
                        <div class="detail-value">${formatDate(order.created_at)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Metode Pembayaran</div>
                        <div class="detail-value">${order.payment_type || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status Pembayaran</div>
                        <div class="detail-value">${order.transaction_status || '-'}</div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Informasi Pengiriman</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Nama Penerima</div>
                        <div class="detail-value">${order.nama_penerima || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor Telepon</div>
                        <div class="detail-value">${order.nomor_penerima || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Alamat Pengiriman</div>
                        <div class="detail-value">${order.alamat_penerima || '-'}</div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Produk yang Dipesan</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${order.items ? order.items.map(item => `
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <img src="${item.gambar_satu}" class="product-image" alt="${item.nama_produk}">
                                        <div>${item.nama_produk}</div>
                                    </div>
                                </td>
                                <td>Rp ${formatNumber(item.harga_order)}</td>
                                <td>${item.jumlah_order}</td>
                                <td>Rp ${formatNumber(item.harga_order * item.jumlah_order)}</td>
                            </tr>
                        `).join('') : '<tr><td colspan="4">Tidak ada item</td></tr>'}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Pesanan:</strong></td>
                            <td><strong>Rp ${formatNumber(order.total_harga)}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="action-buttons">
                    <button id="printNota" class="btn-print" onclick="generateNota('${order.order_id}')">
                        <i class="fas fa-print"></i> Cetak Nota
                    </button>
                </div>
            </div>
        `;

                } catch (error) {
                    // console.error('Error fetching order details:', error);
                    content.innerHTML = `
            <div class="error-message" style="text-align: center; padding: 20px; color: #dc3545;">
                <p>Gagal memuat detail pesanan: ${error.message}</p>
                <button onclick="closeModal()" class="btn-secondary">Tutup</button>
            </div>
        `;
                }
            }
        });

        // Helper functions
        function getStatusLabel(status) {
            const labels = {
                'pending': 'Menunggu Pembayaran',
                'settlement': 'Sudah Dibayar',
                'processing': 'Sedang Diproses',
                'shipped': 'Dikirim',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return labels[status?.toLowerCase()] || status || 'Unknown';
        }

        function formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat('id-ID').format(number);
        }


        // Function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

        // Function to format date
        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Modal control functions
        window.closeModal = function () {
            document.getElementById('orderDetailModal').style.display = "none";
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('orderDetailModal');
            const closeBtn = document.querySelector('.close');

            window.onclick = function (event) {
                if (event.target == modal) {
                    closeModal();
                }
            }

            if (closeBtn) {
                closeBtn.onclick = closeModal;
            }
        });
    </script>
    <script src="..\resources\js\Order.js"></script>
    <script src="..\resources\js\CetakNota.js"></script>

</body>

</html>
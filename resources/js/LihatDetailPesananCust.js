document.addEventListener("DOMContentLoaded", function () {
    // Get all tab buttons and order cards
    const tabButtons = document.querySelectorAll(".tab-button");
    const orderCards = document.querySelectorAll(".order-card");
    const defaultStatus = "pending"; // Set default status to pending

    // Function to filter orders
    function filterOrders(status) {
        orderCards.forEach((card) => {
            card.style.display =
                card.dataset.status === status ? "block" : "none";
        });

        // Update active tab styling
        tabButtons.forEach((button) => {
            button.classList.toggle("active", button.dataset.status === status);
        });

        // Optional: Update URL with status parameter
        updateURL(status);
    }

    // Function to update URL with status parameter
    function updateURL(status) {
        const url = new URL(window.location);
        url.searchParams.set("status", status);
        window.history.pushState({}, "", url);
    }

    // Add click event to tab buttons
    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            filterOrders(button.dataset.status);
        });
    });

    // Check URL parameters for status or use default
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get("status");

    // Initialize with status from URL or default to pending
    const initialStatus = statusParam || defaultStatus;

    // Find and activate the corresponding tab
    const activeTab = document.querySelector(
        `.tab-button[data-status="${initialStatus}"]`
    );
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
    };

    window.viewOrderDetails = async function (orderId) {
        const loadingHtml = `
<div class="loading-spinner" style="text-align: center; padding: 20px;">
    <div class="spinner"></div>
    <p>Memuat detail pesanan...</p>
</div>
`;

        const modal = document.getElementById("orderDetailModal");
        const content = document.getElementById("orderDetailContent");

        // Show modal with loading state
        content.innerHTML = loadingHtml;
        modal.style.display = "block";

        try {
            // console.log('Fetching order details for:', orderId);
            const response = await fetch(
                `../config/get_order_details.php?order_id=${orderId}`,
                {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest", // Tandai sebagai AJAX request
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            // console.log("Data received:", data);

            if (!data.success) {
                throw new Error(
                    data.message || "Failed to fetch order details"
                );
            }

            const order = data.order;

            // Format order details HTML
            content.innerHTML = `
    <div class="order-detail-header">
        <h2>Detail Pesanan</h2>
        <span class="order-status status-${
            order.transaction_status?.toLowerCase() || "pending"
        }">${getStatusLabel(order.transaction_status)}</span>
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
                <div class="detail-value">${order.payment_type || "-"}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status Pembayaran</div>
                <div class="detail-value">${
                    order.transaction_status || "-"
                }</div>
            </div>
        </div>
    </div>

    <div class="detail-section">
            <h3>Informasi Pengiriman</h3>
            <div class="detail-grid">
                <!-- Informasi Penerima -->
                <div class="detail-item">
                    <div class="detail-label">Nama Penerima</div>
                    <div class="detail-value">${
                        order.nama_penerima || "-"
                    }</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Telepon</div>
                    <div class="detail-value">${
                        order.nomor_penerima || "-"
                    }</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Alamat Pengiriman</div>
                    <div class="detail-value">${
                        order.alamat_penerima || "-"
                    }</div>
                </div>

                <!-- Informasi Ekspedisi -->
                <div class="detail-item">
                    <div class="detail-label">Ekspedisi</div>
                    <div class="detail-value">
                        <span class="expedition-badge">
                            ${order.shipments[0]?.ekspedisi || "-"} 
                        </span>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Resi</div>
                    <div class="detail-value">
                        ${
                            order.shipments[0]?.nomor_resi
                                ? `<span class="tracking-number">${order.shipments[0]?.nomor_resi}</span>`
                                : '<span class="pending">Menunggu nomor resi</span>'
                        }
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Biaya Ongkir</div>
                    <div class="detail-value">
                        Rp ${
                            order.shipments[0]?.biaya_ongkir
                                ? Number(
                                      order.shipments[0].biaya_ongkir
                                  ).toLocaleString()
                                : "-"
                        }
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Estimasi Pengiriman</div>
                    <div class="detail-value">
                        ${
                            order.shipments[0]?.estimasi_sampai
                                ? `${order.shipments[0].estimasi_sampai} hari`
                                : "-"
                        }
                    </div>
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
                ${
                    order.items
                        ? order.items
                              .map(
                                  (item) => `
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="${
                                    item.gambar_satu
                                }" class="product-image" alt="${
                                      item.nama_produk
                                  }">
                                <div>${item.nama_produk}</div>
                            </div>
                        </td>
                        <td>Rp ${formatNumber(item.harga_order)}</td>
                        <td>${item.jumlah_order}</td>
                        <td>Rp ${formatNumber(
                            item.harga_order * item.jumlah_order
                        )}</td>
                    </tr>
                `
                              )
                              .join("")
                        : '<tr><td colspan="4">Tidak ada item</td></tr>'
                }
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Pesanan:</strong></td>
                    <td><strong>Rp ${formatNumber(
                        order.total_harga
                    )}</strong></td>
                </tr>
            </tfoot>
        </table>
        <div class="action-buttons">
            <button id="printNota" class="btn-print" onclick="generateNota('${
                order.order_id
            }')">
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
    };
});

// Helper functions
function getStatusLabel(status) {
    const labels = {
        pending: "Menunggu Pembayaran",
        settlement: "Sudah Dibayar",
        processing: "Sedang Dikemas",
        shipped: "Dalam Pengiriman",
        delivered: "Selesai",
        cancelled: "Dibatalkan",
    };
    return labels[status?.toLowerCase()] || status || "Unknown";
}

function formatNumber(number) {
    if (!number) return "0";
    return new Intl.NumberFormat("id-ID").format(number);
}

// Function to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(amount);
}

// Function to format date
function formatDate(dateString) {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
}

// Modal control functions
window.closeModal = function () {
    document.getElementById("orderDetailModal").style.display = "none";
};

// Event listeners
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("orderDetailModal");
    const closeBtn = document.querySelector(".close");

    window.onclick = function (event) {
        if (event.target == modal) {
            closeModal();
        }
    };

    if (closeBtn) {
        closeBtn.onclick = closeModal;
    }
});

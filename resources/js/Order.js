// Order.js

class PaymentHandler {
    constructor(config = {}) {
        this.form = document.getElementById("payment-form");
        this.payButton = document.getElementById("pay-btn");
        this.isExistingOrder = Boolean(config.orderId);
        this.orderId = config.orderId;
        this.payButton = config.buttonElement;

        // Cek apakah ini untuk existing order atau new order
        this.isExistingOrder = config.orderId ? true : false;

        // Hanya inisialisasi form jika bukan existing order
        if (!this.isExistingOrder) {
            this.form = document.getElementById("payment-form");
            if (!this.form) {
                // console.log(
                // "Info: Payment form not found - Existing order mode"
                // );
            }
        }

        // Set button berdasarkan config atau default
        this.payButton =
            config.buttonElement || document.getElementById("pay-btn");
        this.orderId = config.orderId;
        this.skipValidation = config.skipValidation || false;

        // Initialize event listeners hanya untuk order baru
        if (!this.isExistingOrder && this.form) {
            this.initializeEventListeners();
        }
    }

    initializeEventListeners() {
        if (this.form && this.payButton) {
            this.payButton.addEventListener("click", (e) =>
                this.handlePayment(e)
            );
        }
    }

    async handlePayment(e) {
        e.preventDefault();

        try {
            // Validasi form sebelum submit
            if (!this.validateForm()) {
                return;
            }

            // Update UI - menunjukkan loading state
            this.setLoadingState(true);

            // Ambil form data
            const formData = new FormData(this.form);

            // Debug: Log form data
            this.logFormData(formData);

            // Kirim ke backend
            const response = await fetch("../config/process_payment.php", {
                method: "POST",
                body: formData,
            });

            // Debug: Log response status
            // console.log("Response status:", response.status);

            const text = await response.text();
            console.log("Raw response:", text);

            if (!response.ok) {
                throw new Error(text || "Server error");
            }

            await this.handlePaymentResponse(text);
        } catch (error) {
            console.error("Error:", error);
            this.showError(error.message);
        } finally {
            this.setLoadingState(false);
        }
    }

    validateForm() {
        // Validasi basic form fields
        const requiredFields = [
            "namapenerima",
            "notelppenerima",
            "alamatpenerima",
            "tanggalacara",
            "lokasiacara",
        ];

        for (const field of requiredFields) {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (!input || !input.value.trim()) {
                this.showError(`Field ${field} wajib diisi`);
                input?.focus();
                return false;
            }
        }

        // Validasi format nomor telepon
        const phoneInput = this.form.querySelector('[name="notelppenerima"]');
        const phoneNumber = phoneInput.value.trim();
        if (!/^(\+62|62|0)[0-9]{9,12}$/.test(phoneNumber)) {
            this.showError(
                "Format nomor telepon tidak valid. Gunakan format: +62/62/0"
            );
            phoneInput.focus();
            return false;
        }

        return true;
    }

    async handleExistingOrder() {
        try {
            if (!this.orderId || !this.payButton) {
                throw new Error("Invalid order configuration");
            }

            console.log("Sending order_id:", this.orderId); // Debug log

            this.setLoadingState(true);

            // Ubah cara pengiriman data
            const response = await fetch(
                "../config/process_existing_payment.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        order_id: this.orderId,
                    }),
                }
            );

            const text = await response.text();
            console.log("Server response:", text);

            try {
                const data = JSON.parse(text);
                if (data.status === "error") {
                    throw new Error(data.message);
                }
                if (data.snap_token) {
                    await this.initiateMidtransPayment(data.snap_token);
                } else {
                    throw new Error("Invalid server response");
                }
            } catch (e) {
                throw new Error(text || "Failed to process server response");
            }
        } catch (error) {
            console.error("Payment Error:", error);
            this.displayError(error.message);
        } finally {
            this.setLoadingState(false);
        }
    }

    async handlePaymentResponse(text) {
        try {
            // Coba parse sebagai JSON
            const data = JSON.parse(text);
            if (data.error) {
                throw new Error(data.error);
            }
            // Jika berhasil di-parse sebagai JSON tapi bukan error,
            // mungkin ini respons sukses dalam format JSON
            if (data.snap_token) {
                await this.initiateMidtransPayment(data.snap_token);
            }
        } catch (e) {
            // Jika bukan JSON, asumsikan ini adalah snap token langsung
            if (text.trim()) {
                await this.initiateMidtransPayment(text.trim());
            } else {
                throw new Error("Received empty response from server");
            }
        }
    }

    async initiateMidtransPayment(snapToken) {
        return new Promise((resolve, reject) => {
            window.snap.pay(snapToken, {
                onSuccess: (result) => {
                    // console.log("Payment success:", result);
                    // Simpan hasil pembayaran ke session storage
                    sessionStorage.setItem(
                        "paymentResult",
                        JSON.stringify(result)
                    );
                    setTimeout(() => {
                        window.location.href = `../customer/notificationHandled/success.php?order_id=${result.order_id}`;
                    }, 1000);
                    resolve(result);
                },
                onPending: (result) => {
                    // console.log("Payment pending:", result);
                    sessionStorage.setItem(
                        "paymentResult",
                        JSON.stringify(result)
                    );
                    setTimeout(() => {
                        window.location.href = `../customer/notificationHandled/pending.php?order_id=${result.order_id}`;
                    }, 1000);
                    resolve(result);
                },
                onError: (result) => {
                    // console.error("Payment error:", result);
                    this.showError(
                        "Pembayaran gagal: " + result.status_message
                    );
                    payButton.disabled = false;
                    payButton.textContent = "Bayar Sekarang";
                    reject(result);
                },
                onClose: () => {
                    // console.log(
                    // "Customer closed the popup without finishing payment"
                    // );
                    this.showWarning("Pembayaran dibatalkan");
                    reject(new Error("Payment popup closed"));
                },
            });
        });
    }

    setLoadingState(isLoading) {
        if (this.payButton) {
            if (isLoading) {
                this.payButton.disabled = true;
                this.payButton.innerHTML =
                    '<span class="spinner"></span> Memproses...';
            } else {
                this.payButton.disabled = false;
                this.payButton.textContent = "Bayar Sekarang";
            }
        }
    }

    sshowError(message) {
        // Tampilkan error message dalam div baru
        const errorDiv = document.createElement("div");
        errorDiv.className = "alert alert-danger";
        errorDiv.textContent = message;

        // Hapus error message yang sudah ada
        const existingAlerts = document.querySelectorAll(".alert");
        existingAlerts.forEach((alert) => alert.remove());

        // Masukkan error message sebelum button atau di parentnya
        if (this.payButton && this.payButton.parentNode) {
            this.payButton.parentNode.insertBefore(errorDiv, this.payButton);
        }

        // Hapus error message setelah 5 detik
        setTimeout(() => errorDiv.remove(), 5000);
    }

    showWarning(message) {
        const warningDiv = document.createElement("div");
        warningDiv.className = "alert alert-warning";
        warningDiv.textContent = message;

        const existingAlerts = document.querySelectorAll(".alert");
        existingAlerts.forEach((alert) => alert.remove());

        this.form.parentNode.insertBefore(warningDiv, this.form);

        setTimeout(() => warningDiv.remove(), 5000);
    }

    logFormData(formData) {
        // console.log("Form Data:");
        for (let pair of formData.entries()) {
            // console.log(pair[0] + ": " + pair[1]);
        }
    }
}

// Initialize payment handler
document.addEventListener("DOMContentLoaded", () => {
    new PaymentHandler();
});

// Global function untuk memanggil payment
window.payOrder = function (orderId) {
    try {
        // Cari button yang diklik
        const button = document.querySelector(
            `button[onclick="payOrder('${orderId}')"]`
        );
        if (!button) {
            throw new Error("Payment button not found");
        }

        // Buat instance PaymentHandler
        const paymentHandler = new PaymentHandler({
            buttonElement: button,
            orderId: orderId,
        });

        // Handle payment
        paymentHandler.handleExistingOrder();
    } catch (error) {
        console.error("Payment initialization error:", error);
        alert("Gagal memulai pembayaran: " + error.message);
    }
};

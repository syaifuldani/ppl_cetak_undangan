// order.js

class OrderPayment {
    constructor() {
        this.form = document.querySelector("form");
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        this.form.addEventListener("submit", this.handleSubmit.bind(this));
    }

    async handleSubmit(e) {
        e.preventDefault();

        try {
            await this.processPayment();
        } catch (error) {
            console.error("Error during payment:", error);
            this.showError("Terjadi kesalahan dalam memproses pembayaran");
        }
    }

    async processPayment() {
        // Show loading indicator
        this.showLoading(true);

        try {
            // Get form data
            const formData = new FormData(this.form);

            // Send request to backend
            const response = await fetch("process_payment.php", {
                method: "POST",
                body: formData,
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const snapToken = await response.text();

            // Handle Midtrans popup
            await this.openMidtransPopup(snapToken);
        } catch (error) {
            throw error;
        } finally {
            // Hide loading indicator
            this.showLoading(false);
        }
    }

    openMidtransPopup(snapToken) {
        return new Promise((resolve, reject) => {
            window.snap.pay(snapToken, {
                onSuccess: (result) => {
                    console.log("Payment success:", result);
                    this.showSuccess("Pembayaran berhasil!");
                    window.location.href = `success.php?order_id=${result.order_id}`;
                    resolve(result);
                },
                onPending: (result) => {
                    console.log("Payment pending:", result);
                    this.showInfo("Silakan selesaikan pembayaran Anda");
                    window.location.href = `pending.php?order_id=${result.order_id}`;
                    resolve(result);
                },
                onError: (result) => {
                    console.log("Payment error:", result);
                    this.showError("Pembayaran gagal!");
                    reject(result);
                },
                onClose: () => {
                    console.log(
                        "Customer closed the popup without finishing payment"
                    );
                    this.showWarning(
                        "Anda menutup popup pembayaran sebelum menyelesaikan pembayaran"
                    );
                    reject(new Error("Popup closed"));
                },
            });
        });
    }

    // Utility methods for UI feedback
    showLoading(show) {
        // Implement loading indicator logic
        const loadingElement = document.getElementById("loading-indicator");
        if (loadingElement) {
            loadingElement.style.display = show ? "block" : "none";
        }
    }

    showSuccess(message) {
        this.showAlert(message, "success");
    }

    showError(message) {
        this.showAlert(message, "error");
    }

    showInfo(message) {
        this.showAlert(message, "info");
    }

    showWarning(message) {
        this.showAlert(message, "warning");
    }

    showAlert(message, type) {
        // Implement your alert/notification system
        // Bisa menggunakan library seperti SweetAlert2 atau sistem notifikasi custom
        alert(message);
    }
}

// Initialize when document is ready
document.addEventListener("DOMContentLoaded", () => {
    new OrderPayment();
});

// Export class if using modules
export default OrderPayment;

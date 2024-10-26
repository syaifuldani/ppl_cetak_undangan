function debounce(func, wait) {
    let timeout;
    return function () {
        const context = this,
            args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}
async function processPayment() {
    const transactionDetails = {
        order_id: Math.floor(Math.random() * 1000000),
        gross_amount: 10000,
    };

    const customerDetails = {
        first_name: "budi",
        last_name: "pratama",
        email: "budi.pra@example.com",
        phone: "08111222333",
    };

    const data = {
        transaction_details: transactionDetails,
        customer_details: customerDetails,
    };

    try {
        const response = await fetch("../config/midtrans_config.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`HTTP status ${response.status}`);
        }

        const token = await response.text();
        console.log("Received token:", token);
        // window.snap.pay(token);
    } catch (error) {
        console.error("Error during payment processing:", error.message);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const payButton = document.getElementById("pay-btn");
    if (payButton) {
        payButton.addEventListener("click", debounce(processPayment, 300)); // Debounce to prevent multiple submissions
    } else {
        console.error("Pay button not found on the page.");
    }
});

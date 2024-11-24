// <!-- Pindahkan script ke awal body -->
function showAlert(message, type) {
    const alertContainer = document.getElementById("alert-container");

    // Buat element alert
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;

    // Tambahkan konten alert
    alertDiv.innerHTML = `
            ${message}
            <button class="close-btn" onclick="closeAlert(this.parentElement)"></button>
        `;

    // Tambahkan alert ke container
    alertContainer.appendChild(alertDiv);

    // Hapus alert setelah 3 detik
    setTimeout(() => {
        closeAlert(alertDiv);
    }, 5000);
}

function closeAlert(alertElement) {
    // Tambahkan animasi slide out
    alertElement.style.animation = "slideOut 0.5s ease-out";

    // Hapus element setelah animasi selesai
    setTimeout(() => {
        alertElement.remove();
    }, 500);
}

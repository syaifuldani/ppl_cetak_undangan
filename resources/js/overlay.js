window.onload = function() {
    // Cek apakah URL mengandung parameter 'success'
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        // Jika ada parameter success, panggil fungsi showOverlay dengan pesan sukses
        showOverlay('Produk berhasil ditambahkan ke keranjang.');
    } else if (urlParams.has('error')) {
        // Jika ada parameter error, tampilkan pesan gagal dengan alert
        alert('Gagal menambahkan produk ke keranjang.');
    }
};

function showOverlay(message) {
    const overlay = document.getElementById('overlay');
    const overlayMessage = document.getElementById('overlayMessage');

    overlayMessage.innerText = message; // Set pesan ke overlay
    overlay.style.display = 'flex'; // Tampilkan overlay dengan display flex untuk centering
}

function hideOverlay() {
    const overlay = document.getElementById('overlay');
    overlay.style.display = 'none'; // Sembunyikan overlay
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantityInput');
    let quantity = parseInt(quantityInput.value, 10);
    if (!isNaN(quantity) && quantity > 1) {
        quantityInput.value = quantity - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantityInput');
    let quantity = parseInt(quantityInput.value, 10);
    if (!isNaN(quantity)) {
        quantityInput.value = quantity + 1;
    }
}

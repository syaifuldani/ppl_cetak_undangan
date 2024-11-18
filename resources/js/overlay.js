// Fungsi untuk menampilkan overlay
function showOverlay(message) {
    const overlay = document.getElementById('overlay');
    const overlayMessage = document.getElementById('overlayMessage');
    overlayMessage.innerText = message; 
    overlay.style.display = 'flex'; 
}

// Fungsi untuk menyembunyikan overlay
function hideOverlay() {
    const overlay = document.getElementById('overlay');
    overlay.style.display = 'none'; 
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

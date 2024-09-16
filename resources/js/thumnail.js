function changeImage(element) {
    // Mengubah gambar utama dengan gambar thumbnail yang diklik
    const mainImage = document.getElementById('mainImage');
    mainImage.src = element.src;
}

function increaseQuantity() {
    // Menambah jumlah kuantitas
    const quantityInput = document.getElementById('quantityInput');
    let quantity = parseInt(quantityInput.value);
    quantityInput.value = quantity + 1;
}

function decreaseQuantity() {
    // Mengurangi jumlah kuantitas, minimal 1
    const quantityInput = document.getElementById('quantityInput');
    let quantity = parseInt(quantityInput.value);
    if (quantity > 1) {
        quantityInput.value = quantity - 1;
    }
}

function showOverlay() {
    document.getElementById("overlay").style.display = "flex";
}

function hideOverlay() {
    document.getElementById("overlay").style.display = "none";
}

function increaseQuantity() {
    let input = document.getElementById("quantityInput");
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity() {
    let input = document.getElementById("quantityInput");
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function zoomImage(event) {
    const image = document.getElementById('mainImage');
    const imageContainer = image.parentElement;
    const containerRect = imageContainer.getBoundingClientRect();

    // Dapatkan posisi relatif mouse di dalam container
    const offsetX = event.clientX - containerRect.left;
    const offsetY = event.clientY - containerRect.top;

    // Hitung persentase posisi mouse di dalam container
    const percentX = (offsetX / containerRect.width) * 100;
    const percentY = (offsetY / containerRect.height) * 100;

    // Atur posisi background image (efek zoom)
    image.style.transformOrigin = `${percentX}% ${percentY}%`;
    image.style.transform = "scale(2)";
}

function resetImage() {
    const image = document.getElementById('mainImage');
    image.style.transform = "scale(1)";
}

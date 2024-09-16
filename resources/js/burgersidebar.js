// Ambil elemen burger icon dan sidebar
const burgerIcon = document.getElementById('burger-icon');
const sidebar = document.getElementById('sidebar');

// Tambahkan event listener ke burger icon untuk mendeteksi klik
burgerIcon.addEventListener('click', function() {
    // Toggle class 'open' untuk burger icon dan sidebar
    burgerIcon.classList.toggle('open');
    sidebar.classList.toggle('open');
});

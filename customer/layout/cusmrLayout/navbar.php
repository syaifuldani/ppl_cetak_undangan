<div class="logo">
    <img src="../resources/img/icons/pleart.png" alt="Logo" class="logo-image">
    <p>PleeArt.</p>
</div>

<div class="center-items">
    <ul class="nav-links">
        <div class="search-bar">
            <form action="" class="search-input">
                <label><img src="../resources/img/icons/search.png" alt=""></label>
                <input type="text" placeholder="Cari">
            </form>
        </div>
        <li><a href="dashboard.php" class="home">Home</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn">
                Cetak Undangan
                <img src="../resources/img/icons/dropdown.png" alt="dropdown">
            </a>
            <div class="dropdown-content">
                <a href="undangan_pernikahan.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Pernikahan
                </a>
                <a href="undangan_khitanan.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Khitanan
                </a>
                <a href="undangan_walimatul.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Walimatul
                </a>
                <a href="undangan_tahlilkirimdoa.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Tahlil & Kirim Doa
                </a>
                <a href="undangan_ulangtahun.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Ulang Tahun
                </a>
            </div>
        </li>
        <li><a href="services/aboutus.php" class="links">About Us</a></li>
        <li><a href="services/contact.php" class="links">Contact</a></li>
    </ul>
</div>

<div class="user-options">
    <!-- Tombol untuk membuka dropdown -->
    <a href="#" class="cart" id="cartButton">
        <img src="../resources/img/icons/shoppingcart.png" alt="Cart">
        <span class="cart-count" id="cart-count"></span>
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- If user is logged in, show profile icon -->
        <div class="customer-dropdown">
            <img src="<?= isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : '../resources/img/profiledefault.png' ?>"
                alt="Profile" class="profile-photo dropdown-toggle">
            <!-- <img src="</?= isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : '../resources/img/profiledefault.png' ?>"
            alt="Profile" class="profile-photo dropdown-toggle"> -->
            <ul class="dropdown-menu">
                <!-- Add user info (name and email) here -->
                <li class="user-info">
                    <strong><?= $_SESSION['user_name']; ?></strong><br>
                    <small><?= $_SESSION['user_email']; ?></small>
                </li>
                <hr> <!-- Optional: Add a horizontal line to separate user info from options -->

                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

    <?php else: ?>
        <!-- If user is not logged in, show Sign in and Register links -->
        <a href="login.php" class="sign-in">Sign in</a>
        <a href="register.php" class="register">Register</a>
    <?php endif; ?>
</div>


<div class="cart-dropdown" id="cartDropdown" style="display: none;">
    <h3>Keranjang</h3>

    <!-- Contoh item keranjang statis -->
    <div class="cart-item">
        <img src="../resources/img/icons/contohproduct.jpeg" alt="Product">
        <div class="item-details">
            <h4>Undangan Blangko Pernikahan</h4>
            <p>Qty: 200</p>
            <p>Rp. 200.000,00</p>
        </div>
    </div>
    <div class="cart-item">
        <img src="../resources/img/icons/contohproduct.jpeg" alt="Product">
        <div class="item-details">
            <h4>Undangan Blangko Pernikahan</h4>
            <p>Qty: 200</p>
            <p>Rp. 200.000,00</p>
        </div>
    </div>
    <div class="cart-item">
        <img src="../resources/img/icons/contohproduct.jpeg" alt="Product">
        <div class="item-details">
            <h4>Undangan Blangko Pernikahan</h4>
            <p>Qty: 200</p>
            <p>Rp. 200.000,00</p>
        </div>
    </div>

    <p class="total-price">Total Harga : Rp. 200.000,00</p>
    <div class="cart-btn">
        <a href="cart.php" class="cart-btn">
            Check Out Sekarang
        </a>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Mengambil elemen tombol keranjang, dropdown, dan count
    const cartButton = document.getElementById('cartButton');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartCount = document.querySelector('.cart-count');

    // Fungsi untuk menampilkan atau menyembunyikan dropdown keranjang
    function toggleCartDropdown() {
        if (cartDropdown.style.display === 'none' || cartDropdown.style.display === '') {
            cartDropdown.style.display = 'block';
        } else {
            cartDropdown.style.display = 'none';
        }
    }

    // Fungsi untuk menghitung jumlah item di dalam keranjang
    function updateCartCount() {
        // Menghitung jumlah elemen dengan kelas .cart-item di dalam dropdown
        const cartItems = document.querySelectorAll('.cart-dropdown .cart-item');
        const itemCount = cartItems.length;

        // Jika ada item di keranjang, tampilkan jumlah item, jika tidak, sembunyikan elemen cart-count
        if (itemCount > 0) {
            cartCount.textContent = itemCount; // Ubah jumlah count
            cartCount.style.display = 'inline'; // Tampilkan count
        } else {
            cartCount.style.display = 'none'; // Sembunyikan count
        }
    }

    // Event listener pada tombol keranjang
    cartButton.addEventListener('click', function (event) {
        event.preventDefault(); // Mencegah link agar tidak langsung mengarahkan ke URL lain
        toggleCartDropdown(); // Memanggil fungsi untuk menampilkan atau menyembunyikan dropdown
    });

    // Event listener untuk menutup dropdown jika klik di luar area dropdown
    document.addEventListener('click', function (event) {
        if (!cartButton.contains(event.target) && !cartDropdown.contains(event.target)) {
            cartDropdown.style.display = 'none'; // Sembunyikan dropdown
        }
    });

    // Panggil fungsi updateCartCount untuk memperbarui count awal
    updateCartCount();
</script>
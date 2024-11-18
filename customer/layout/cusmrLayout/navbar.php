<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <div class="logo">
        <img src="../resources/img/icons/pleart.png" alt="Logo" class="logo-image">
        <p>PleeArt.</p>
        <div class="burger" id="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </div>

    <div class="center-items">
        <ul class="nav-links" id="nav-links">
            <div class="search-bar">
                <form action="" class="search-input">
                    <label><img src="../resources/img/icons/search.png" alt=""></label>
                    <input type="text" placeholder="Cari">
                </form>
            </div>
            <li><a href="dashboard.php" class="home">Home</a></li>
            <li class="dropdown">
                <button class="dropbtn">
                    Cetak Undangan
                    <img src="../resources/img/icons/dropdown.png" alt="dropdown">
                </button>
                <div class="dropdown-content">
                    <a href="undangan_pernikahan.php">
                        <img src="../resources/img/icons/paper.png" alt="dropdown">
                        Pernikahan
                    </a>
                    <a href="undangan_khitanan.php">
                        <img src="../resources/img/icons/paper.png" alt="dropdown">
                        Khitan
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php
                // Ambil item keranjang untuk pengguna yang sudah login
                $cartItems = getCartItems($_SESSION['user_id']);
                $itemCount = count($cartItems); // Hitung jumlah item di keranjang
                ?>
                <span class="cart-count" id="cart-count" style="<?= $itemCount > 0 ? 'display: inline' : 'display: none'; ?>">
                    <?= $itemCount; ?>
                </span>
            <?php else: ?>
                <span class="cart-count" id="cart-count" style="display: none;"></span>
            <?php endif; ?>
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

                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

        <?php else: ?>
            <!-- If user is not logged in, show Sign in and Register links -->
            <a href="login.php" class="sign-in">Login</a>
            <a href="register.php" class="register">Register</a>
        <?php endif; ?>
    </div>


    <div class="cart-dropdown" id="cartDropdown" style="display: none;">
        <h3>Keranjang</h3>

        <div class="cart-items-container"> 
            <?php
            // Cek apakah pengguna sudah login
            if (isset($_SESSION['user_id'])) {
                // Panggil fungsi untuk mendapatkan item keranjang
                $cartItems = getCartItems($_SESSION['user_id']);

                // Cek apakah ada item di keranjang
                if (!empty($cartItems)) {
                    // Tampilkan setiap item di keranjang
                    foreach ($cartItems as $item) {
            ?>
                        <div class="cart-item">
                            <img src="<?= $item['gambar_satu'] ?>" alt="Product" class="cart-item-image">
                            <div class="item-details">
                                <h4 class="item-name"><?= $item['nama_produk'] ?></h4>
                                <p class="item-qty">Qty: <?= $item['jumlah'] ?></p>
                                <p class="item-price">Rp. <?= number_format($item['harga_produk'], 2, ',', '.') ?></p>
                            </div>
                        </div>
            <?php
                    }

                    // Menghitung total harga
                    $totalHarga = 0;
                    foreach ($cartItems as $item) {
                        $totalHarga += $item['harga_produk'] * $item['jumlah']; // Asumsikan harga_produk adalah per item
                    }
                } else {
                    // Jika tidak ada item di keranjang
                    echo '<p class="empty-cart-message">Keranjang kosong.</p>';
                }
            } else {
                // Pesan untuk pengguna yang belum login
                echo '<p class="login-prompt">Silahkan Login Terlebih Dahulu!</p>';
            }
            ?>
        </div> 

        <?php if (!empty($cartItems)) { ?>
            <p class="total-price">Total Harga: Rp. <?= number_format($totalHarga, 2, ',', '.') ?></p>
        <?php } ?>

        <div class="cart-btn">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php" class="cart-btn">
                    Check Out Sekarang
                </a>
            <?php else: ?>
                <a href="login.php" class="cart-btn">
                    Login Dulu Ga Sihh!
                </a>
            <?php endif; ?>
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

        // Event listener pada tombol keranjang
        cartButton.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah link agar tidak langsung mengarahkan ke URL lain
            toggleCartDropdown(); // Memanggil fungsi untuk menampilkan atau menyembunyikan dropdown
        });

        // Event listener untuk menutup dropdown jika klik di luar area dropdown
        document.addEventListener('click', function(event) {
            if (!cartButton.contains(event.target) && !cartDropdown.contains(event.target)) {
                cartDropdown.style.display = 'none'; // Sembunyikan dropdown
            }
        });
    </script>
</body>

</html>
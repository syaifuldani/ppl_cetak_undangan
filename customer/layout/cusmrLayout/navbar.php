<div class="logo">
    <img src="../resources/img/icons/pleart.png" alt="Logo" class="logo-image">
    <p>PleeArt.</p>
</div>

<div class="center-items">
    <ul class="nav-links">
        <div class="search-bar">
            <input type="text" placeholder="Cari">
            <button type="submit"><img src="../resources/img/icons/search.png" alt=""></button>
        </div>
        <li><a href="dashboard.php" class="home">Home</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn">
                Cetak Undangan
                <img src="../resources/img/icons/dropdown.png" alt="dropdown">
            </a>
            <div class="dropdown-content">
                <a href="pernikahan.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Pernikahan
                </a>
                <a href="khitanan.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Khitanan
                </a>
                <a href="walimatul.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Walimatul
                </a>
                <a href="tahlilkirimdoa.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Tahlil & Kirim Doa
                </a>
                <a href="ulangtahun.php">
                    <img src="../resources/img/icons/paper.png" alt="dropdown">
                    Ulang Tahun
                </a>
            </div>
        </li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
</div>

<div class="user-options">
    <a href="#" class="cart">
        <img src="../resources/img/icons/shoppingcart.png" alt="Cart">
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- If user is logged in, show profile icon -->
        <div class="customer-dropdown">
            <img src="../resources/img/profiledefault.png" alt="Profile" class="profile-photo dropdown-toggle">

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <div class="container">
    <div class="sidebar">
            <div class="logo">
                <img src="./style/img/logo.png" alt="Logo">
                <span>PLEE.ART</span>
            </div>
            <ul>
                <li>
                    <button class="dashboard-button">
                    <a href="dashboard.php">
                        <img src="./style/img/dashboard.png" alt="Logo">
                        <span>Dashboard</span>
                    </a>
                    </button>
                </li>
                <li>
                    <button class="dashboard-button">
                    <a href="./product.php">
                        <img src="./style/img/produk.png" alt="Logo">
                        <span>All Product</span>
                    </a>
                    </button>
                </li>
                <li>
                    <button class="dashboard-button">
                    <a href="./orderlist.php">
                        <img src="./style/img/order.png" alt="Logo">
                        <span>Order List</span>
                    </a>
                    </button>
                </li>
            </ul>
            <div class="categories">
                <h4>Categories</h4>
                <ul>
                    <li><a href="#">Undangan Pernikahan</a></li>
                    <li><a href="#">Undangan Khitan</a></li>
                    <li><a href="#">Undangan Tahlil</a></li>
                </ul>
            </div>
        </div>

        <main class="main-content">
        <header class="header">
            <h2>Dashboard</h2>
            <div class="date">Oct 11, 2023 - Nov 11, 2022</div>
            <div class="admin-dropdown">
                <button class="dropdown-toggle">Admin ▼</button>
                <ul class="dropdown-menu">
                    <li><a href="../profile/profile.php">Profile</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </div>
         </header>


            <section class="dashboard-cards">
                <div class="card">
                    <h3>Total Orders</h3>
                    <p>1</p>
                    <small>Compared to Oct 2023</small>
                </div>
                <div class="card">
                    <h3>Total Orders</h3>
                    <p>0</p>
                    <small>Compared to Oct 2022</small>
                </div>
            </section>

            <section class="chart-section">
                <h3>Grafik Penjualan</h3>
                <div class="chart-controls">
                    <button>Mingguan</button>
                    <button>Bulan</button>
                    <button>Tahunan</button>
                </div>
                <div class="chart">
                  
                </div>
            </section>

            <section class="sales-section">
                <h3>Penjualan Terbanyak</h3>
                <div class="sales-list">
                    <div class="sales-item">
                        <p>Undangan Pernikahan</p>
                        <p>Rp. 126.50 (250 sales)</p>
                    </div>
                    <div class="sales-item">
                        <p>Undangan Khitan</p>
                        <p>Rp. 126.50 (100 sales)</p>
                    </div>
                    <div class="sales-item">
                        <p>Undangan Ulang Tahun</p>
                        <p>Rp. 126.50 (66 sales)</p>
                    </div>
                    <button>Report</button>
                </div>
            </section>

            <section class="recent-orders">
                <h3>Pesanan Terbaru</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lorem Ipsum</td>
                            <td>#25425</td>
                            <td>Nov 8th, 2023</td>
                            <td>Kavin</td>
                            <td>Delivered</td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td>Lorem Ipsum</td>
                            <td>#25423</td>
                            <td>Nov 6th, 2023</td>
                            <td>Komal</td>
                            <td>Cancelled</td>
                            <td>₹200.00</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

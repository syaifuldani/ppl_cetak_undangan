<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
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
                    <a href="./dashboard.php">
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
                    <a href="orderlist.php">
                        <img src="./style/img/order.png" alt="Logo">
                        <span>Order List</span>
                    </a>
                    </button>
                </li>
            </ul>
            <div class="categories">
                <h4>Categories</h4>
                <ul>
                    <li><a href="./undangan_pernikahan.php">Undangan Pernikahan</a></li>
                    <li><a href="./undangan_khitan.php">Undangan Khitan</a></li>
                    <li><a href="./undangan_tahlil.php">Undangan Tahlil</a></li>
                </ul>
            </div>
        </div>
        <div class="main">
        <header class="header">
            <h2>Dashboard</h2>
            <div class="date">Oct 11, 2023 - Nov 11, 2022</div>
            <div class="admin-dropdown">
                <button class="dropdown-toggle">Admin ▼</button>
                <ul class="dropdown-menu">
                    <li><a href="./profile/profile.php">Profile</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </div>
         </header>
            <div class="content">
                <h3>Riwayat Penjualan</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
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
                            <td><input type="checkbox"></td>
                            <td>Undangan Pernikahan</td>
                            <td>#25426</td>
                            <td>Nov 8th, 2023</td>
                            <td><img src="kavin.jpg" alt="Kavin"> Kavin</td>
                            <td><span class="delivered">Delivered</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Pernikahan</td>
                            <td>#25425</td>
                            <td>Nov 7th, 2023</td>
                            <td><img src="komael.jpg" alt="Komael"> Komael</td>
                            <td><span class="canceled">Canceled</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Pernikahan</td>
                            <td>#25424</td>
                            <td>Nov 6th, 2023</td>
                            <td><img src="nikhill.jpg" alt="Nikhill"> Nikhill</td>
                            <td><span class="delivered">Delivered</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Khitan</td>
                            <td>#25423</td>
                            <td>Nov 5th, 2023</td>
                            <td><img src="shivam.jpg" alt="Shivam"> Shivam</td>
                            <td><span class="canceled">Canceled</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Khitan</td>
                            <td>#25422</td>
                            <td>Nov 4th, 2023</td>
                            <td><img src="shadab.jpg" alt="Shadab"> Shadab</td>
                            <td><span class="delivered">Delivered</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Khitan</td>
                            <td>#25421</td>
                            <td>Nov 2nd, 2023</td>
                            <td><img src="yogesh.jpg" alt="Yogesh"> Yogesh</td>
                            <td><span class="delivered">Delivered</span></td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Undangan Tahlil</td>
                            <td>#25423</td>
                            <td>Nov 1st, 2023</td>
                            <td><img src="sunita.jpg" alt="Sunita"> Sunita</td>
                            <td><span class="canceled">Canceled</span></td>
                            <td>₹200.00</td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination">
                    <ul>
                        <li class="active">1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>...</li>
                        <li>10</li>
                        <li><a href="#">NEXT ></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
$jenishalaman = "Order list";

?>

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

        <?php require "template/sidebar.php"; ?>

        <div class="main">

            <?php require "template/header.php"; ?>

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
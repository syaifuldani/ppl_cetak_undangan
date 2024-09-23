<?php
$title = "Plee ART";
$jenishalaman = "Dashboard";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "template/sidebar.php"; ?>

        <main class="main-content">

            <?php require "template/header.php"; ?>

            <section class="dashboard-cards">
                <div class="card">
                    <h3>Total Orders</h3>
                    <p>1</p>
                </div>
                <div class="card">
                    <h3>Pesanan Selesai</h3>
                    <p>0</p>
                </div>
            </section>

            <section class="chart-section">
                <h3>Grafik Penjualan</h3>
                <div class="chart-controls">
                    <button>Mingguan</button>
                    <button>Bulan</button>
                    <button>Tahunan</button>
                </div>

                <!-- Cart -->
                <div class="chart">
                    <canvas id="myChart1"></canvas>

                    <canvas id="myChart2"></canvas>
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
                            <td>
                                <select id="cars">
                                    <option value="perludibayar">Perlu Dibayar</option>
                                    <option value="terkirim">Terkirim</option>
                                    <option value="dikemas">Dikemas</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                    <option value="perludikirim">Perlu Dikirim</option>
                                </select>
                            </td>
                            <td>₹200.00</td>
                        </tr>
                        <tr>
                            <td>Lorem Ipsum</td>
                            <td>#25423</td>
                            <td>Nov 6th, 2023</td>
                            <td>Komal</td>
                            <td>
                                <select id="cars">
                                    <option value="perludibayar">Perlu Dibayar</option>
                                    <option value="terkirim">Terkirim</option>
                                    <option value="dikemas">Dikemas</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                    <option value="perludikirim">Perlu Dikirim</option>
                                </select>
                            </td>
                            <td>₹200.00</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="../node_modules/chart.js/dist/chart.umd.js"></script>
    <script>
        const xValues1 = [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150];
        const yValues1 = [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 15];

        new Chart("myChart1", {
            type: "line",
            data: {
                labels: xValues1,
                datasets: [{
                    backgroundColor: "rgba(0,0,255,1.0)",
                    borderColor: "rgba(0,0,255,0.1)",
                    data: yValues1
                }]
            },
            options: {
                maintainAspectRatio: false, // Prevent chart from resizing
                responsive: false, // Disable responsiveness
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 6,
                            max: 16
                        }
                    }],
                }
            }
        });


        var xValues2 = ["Italy", "France", "Spain", "USA", "Argentina"];
        var yValues2 = [55, 49, 44, 24, 15];
        var barColors = ["red", "green", "blue", "orange", "brown"];

        new Chart("myChart2", {
            type: "bar",
            data: {
                labels: xValues2,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues2
                }]
            },
            options: {
                maintainAspectRatio: false, // Prevent chart from resizing
                responsive: false, // Disable responsiveness
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "World Wine Production 2018"
                }
            }
        });
    </script>
</body>

</html>
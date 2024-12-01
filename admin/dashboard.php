<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'admin') {
    // Jika tidak ada session login, redirect ke halaman login
    header("Location: login_admin.php");
    exit();
}

// Include file konfigurasi koneksi database
require '../config/connection.php';
require '../config/function.php';

// Data untuk halaman
$title = "PleeART";
$jenishalaman = "Dashboard";
$user_email = $_SESSION['user_email']; // Email user yang diambil dari session

// Mengambil data menggunakan fungsi yang sudah dibuat
$total_pemesanan = getTotalPemesanan();
$total_penjualan_selesai = getTotalPenjualanSelesai();
$penjualan_per_bulan = getPenjualanPerBulan();
$penjualan_terbanyak = getPenjualanTerbanyak();
$pesanan_terbaru = getPesananTerbaru();
$penjualan_chart = getPenjualanChart();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="icon" href="resources/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./style/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
</head>

<body>
    <div class="container">
        <?php require "template/sidebar.php"; ?>
        <main id="content-to-download" class="main-content">
            <?php require "template/header.php"; ?>
            <!-- Dashboard Cards -->
            <section class="dashboard-cards">
                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?= htmlspecialchars($total_pemesanan) ?></p>
                </div>
                <div class="card">
                    <h3>Pesanan Selesai</h3>
                    <p><?= htmlspecialchars($total_penjualan_selesai) ?></p>
                </div>
            </section>

            <!-- Chart Section -->
            <section class="chart-section">
                <h3>Grafik Penjualan</h3>
                <div class="charts">
                    <div class="chart">
                        <canvas id="myChart1"></canvas>
                    </div>
                    <div class="chart">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
            </section>

            <!-- Sales Section -->
            <section class="recent-orders">
                <h3>Penjualan Terbanyak</h3>
                <div class="sales-list">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penjualan_terbanyak as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($item['jumlah_terjual']) ?> orders</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button id="download-pdf">Download Report</button>
                </div>
            </section>

            <!-- Recent Orders -->
            <section class="recent-orders">
                <h3>Pesanan Terbaru</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Nomor Penerima</th>
                            <th>Alamat</th>
                            <th>Kode Pos</th>
                            <th>Keterangan Order</th>
                            <th>Pembayaran</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesanan_terbaru as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama_penerima']) ?></td>
                            <td><?= htmlspecialchars($item['nomor_penerima']) ?></td>
                            <td><?= htmlspecialchars($item['alamat_penerima']) ?></td>
                            <td><?= htmlspecialchars($item['kodepos']) ?></td>
                            <td><?= htmlspecialchars($item['keterangan_order']) ?></td>
                            <td><?= htmlspecialchars($item['payment_type']) ?></td>
                            <td>Rp.<?= number_format($item['total_harga']) ?></td>
                            <td><?= htmlspecialchars($item['transaction_status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
    // Ambil data dari PHP ke dalam JavaScript
    const bulan = <?php echo json_encode(array_column($penjualan_chart, 'bulan')); ?>;
    const totalPenjualan = <?php echo json_encode(array_column($penjualan_chart, 'total')); ?>;

    // Inisialisasi grafik garis dengan Chart.js
    const ctx1 = document.getElementById('myChart1').getContext('2d');
    const myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: bulan,
            datasets: [{
                label: 'Total Penjualan',
                data: totalPenjualan,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inisialisasi grafik batang dengan Chart.js
    const ctx2 = document.getElementById('myChart2').getContext('2d');
    const myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: bulan,
            datasets: [{
                label: 'Total Penjualan',
                data: totalPenjualan,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script>
    // Fungsi untuk mengunduh halaman sebagai PDF
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('download-pdf').addEventListener('click', function() {
            const element = document.getElementById(
            'content-to-download'); // Tentukan elemen khusus untuk diunduh
            const options = {
                margin: 7, // Mengurangi margin untuk memberi ruang lebih
                filename: 'report_penjualan.pdf', // Nama file PDF
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 0.5, // Mengurangi skala untuk memastikan konten lebih kecil dan muat
                    logging: true, // Logging untuk debugging
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: [490, 500], // Ukuran A2 dalam mm (594mm x 420mm)
                    orientation: 'landscape', // Orientasi landscape
                    pagesplit: true // Membagi konten ke beberapa halaman jika diperlukan
                }
            };
            html2pdf().from(element).set(options)
        .save(); // Mengunduh elemen dengan pengaturan yang ditentukan
        });
    });
    </script>





</body>

</html>
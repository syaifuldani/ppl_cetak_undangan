<?php
// Include koneksi dan konfigurasi
require '../config/connection.php';

// Ambil data dari form
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';

// Nama bulan dalam bahasa Indonesia
$nama_bulan = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

try {
    $db = $GLOBALS['db'];

    // Query data berdasarkan periode
    $query = "SELECT 
                order_id, 
                nama_penerima, 
                nomor_penerima, 
                alamat_penerima, 
                kota, 
                kodepos, 
                keterangan_order, 
                payment_type, 
                transaction_status, 
                total_harga, 
                transaction_time 
              FROM orders WHERE 1";

    $params = [];
    if ($bulan) {
        $query .= " AND MONTH(transaction_time) = ?";
        $params[] = $bulan;
    }
    if ($tahun) {
        $query .= " AND YEAR(transaction_time) = ?";
        $params[] = $tahun;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $periode = ($bulan ? $nama_bulan[$bulan] . ' ' : '') . ($tahun ?: 'Semua Tahun');

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <style>
        .report-container {
            width: 100%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header .logo {
            width: 150px;
            margin: 0 auto;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .btn-download {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .btn-download:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="report-container" id="report-container">
    <div class="header">
        <img src="../resources/img/logo.png" class="logo" alt="Plee Art Logo">
        <h1>Laporan Penjualan - <?= $periode ?></h1>
        <p>
            Jl. A Yani Dsn.Sumberjo Ds.Sumbertanggul Kec. Mojosari<br>
            Kab. Mojokerto, 41382, Jawa Timur Indonesia
        </p>
        <p><strong>Telp:</strong> +62 878-5333-8254</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Penerima</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Total Harga</th>
                <th>Waktu Transaksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['nama_penerima']) ?></td>
                    <td><?= htmlspecialchars($order['alamat_penerima']) ?></td>
                    <td><?= htmlspecialchars($order['kota']) ?></td>
                    <td>Rp. <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($order['transaction_time']) ?></td>
                    <td><?= htmlspecialchars($order['transaction_status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn-download" id="download-btn">Download Laporan PDF</button>
</div>

<script>
    // Menggunakan html2pdf.js untuk mengonversi div menjadi PDF
    document.getElementById('download-btn').addEventListener('click', function () {
        const element = document.getElementById('report-container');
        const button = document.getElementById('download-btn');

        // Sembunyikan tombol download saat konversi PDF
        button.style.display = 'none';

        const options = {
            margin: 10,
            filename: 'Laporan_Penjualan.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        // Proses konversi dan unduh PDF
        html2pdf().from(element).set(options).save().then(() => {
            // Setelah proses selesai, tampilkan kembali tombol download
            button.style.display = 'block';
        });
    });
</script>

</body>
</html>

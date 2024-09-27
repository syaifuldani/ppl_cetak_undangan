<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Anda</title>
    <link rel="stylesheet" href="../resources/css/cart.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include 'layout/cusmrLayout/navbar.php'; ?>
        </nav>

        <div class="content">
            <div class="cart-container">
                <div class="cart-section">
                    <h1>Keranjang Anda!</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Harga Per 1 Kertas
                                </th>
                                <th>
                                    Jumlah
                                </th>
                                <th>
                                    Sub Total
                                </th>
                                <th>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="items">
                                        <img alt="Product Image" class="product-image" height="50" src="../resources/img/icons/contohproduct.jpeg" width="50" />
                                        <p>Undangan Nikah Blanko Biru</p>
                                    </div>
                                </td>
                                <td class="price">
                                    Rp.1000
                                </td>
                                <td>
                                    <div class="quantity-control">
                                        <button>-</button>
                                        <input value="100"/>
                                        <button>+</button>
                                    </div>
                                </td>
                                <td class="subtotal">
                                    Rp.100.000,00
                                </td>
                                <td>
                                    <a href="#">
                                        <img src="../resources/img/icons/trash.png" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                        </tbody>
                    </table>
                    <div class="update-cart-btn">
                        <a href="#" class="update-cart-btn">Perbarui Keranjang</a>
                    </div>
                    <div class="warning-message">
                        Wajib Lengkapi Data Undangan dan Data Pengiriman Anda!!
                    </div>
                    <div class="form-section">
                        <div class="form-group">
                            <h3>Data Undangan</h3>
                            <input type="date" placeholder="Tanggal dan Waktu Acara">
                            <input type="text" placeholder="Tempat/Lokasi Acara">
                            <textarea placeholder="Keterangan Tambahan"></textarea>
                            <p class="info">
                                Tuliskan keterangan tambahan seperti nama orang tua dan calon mempelai, teks doa, nama yang dirayakan, tema acara, atau pesan/informasi penting lainnya sesuai dengan acara pernikahan, khitan, walimatul ursy, tahlil, kirim doa, atau ulang tahun.<br><br>

                                Contoh : <br>
                                Nama : John Doe <br>
                                Teks Doa: "Semoga diberikan keberkahan dan keselamatan dunia akhirat." <br>
                                Dst.
                            </p>
                        </div>
                        <div class="form-group">
                            <h3>Data Alamat Kirim</h3>
                            <input type="text" placeholder="Nama Lengkap Penerima">
                            <input type="text" placeholder="No. Telp Penerima">
                            <textarea placeholder="Alamat Lengkap dan Keterangan"></textarea>
                            <p class="info">
                                Pastikan alamat yang Anda tulis lengkap dan jelas, termasuk nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan, kota/kabupaten, dan kode pos.
                                Jangan lupa sertakan informasi tambahan seperti patokan lokasi (misalnya: "Di sebelah toko A" atau "Dekat dengan kantor B") agar paket dapat dikirimkan dengan tepat. <br><br>
                                Contoh: <br>
                                Nama: John Doe <br>
                                Alamat: Jl. A Yani No. 123, RT 02/RW 03, Dsn.Sumberjo Ds.Sumbertanggul Kec. Mojosari Kab. Mojokerto, 41382 <br>
                                Patokan: Rumah warna putih depannya ada pohon sawo.
                            </p>
                            <p class="choose delivery">
                                Pilih Jasa Pengiriman:
                            </p>
                            <div class="shipping-options">
                                <input type="radio" id="jne" name="shipping" value="JNE">
                                <label for="jne">
                                    <img src="../resources/img/icons/jne-logos.png" alt="JNE">
                                    JNE Express
                                </label>

                                <input type="radio" id="jnt" name="shipping" value="JNT">
                                <label for="jnt">
                                    <img src="../resources/img/icons/jnt-logos.png" alt="J&T">
                                    J&T Express
                                </label>

                                <input type="radio" id="shopee" name="shipping" value="Shopee">
                                <label for="shopee">
                                    <img src="../resources/img/icons/spx-logos.png" alt="Shopee Express">
                                    Shopee Express
                                </label>
                            </div>
                        </div>
                        <button class="pay-btn">Bayar Sekarang</button>
                    </div>
                </div>

                <div class="details-section">
                    <div class="order-history">
                        <h3>Riwayat Pemesanan</h3>
                        <ul>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="#">Lihat Detail →</a>
                            </li>
                            <li>
                                <div class="information">
                                    <img src="../resources/img/icons/li-caption.png" alt="">
                                    <span>05/09/2024</span>
                                </div>
                                <a href="#">Lihat Detail →</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
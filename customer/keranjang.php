<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang</title>
  <link rel="stylesheet" href="../resources/css/keranjang.css">
  <link rel="stylesheet" href="../resources/css/indexhomecsmr.css">
  <link rel="stylesheet" href="../resources/css/navbar.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <?php include '../layout/cusmrLayout/navbar.php'; ?>
        </nav>
        <div>
            <h1>Keranjang Anda!</h1>
            <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="product">
                            <img src="../resources/img/icons/contohproduct.jpeg" alt="Produk Undangan" class="product-image">
                            <span>Undangan Nikah Blanko Biru</span>
                        </td>
                        <td>Rp. 1000</td>
                        <td class="quantity">
                            <button class="quantity-btn" onclick="decreaseQuantity(this)">-</button>
                            <input type="text" value="100" class="quantity-input" readonly>
                            <button class="quantity-btn" onclick="increaseQuantity(this)">+</button>
                        </td>
                        <td>
                            Rp. 100,000.00
                            <button class="delete-btn" onclick="removeRow(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="product">
                            <img src="../resources/img/icons/contohproduct.jpeg" alt="Produk Undangan" class="product-image">
                            <span>Undangan Nikah Blanko Biru</span>
                        </td>
                        <td>Rp. 1000</td>
                        <td class="quantity">
                            <button class="quantity-btn" onclick="decreaseQuantity(this)">-</button>
                            <input type="text" value="100" class="quantity-input" readonly>
                            <button class="quantity-btn" onclick="increaseQuantity(this)">+</button>
                        </td>
                        <td>
                            Rp. 100,000.00
                            <button class="delete-btn" onclick="removeRow(this)">Hapus</button>
                        </td>
                    </tr>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
        </div>
        <div class="forms">
            <form class="form-invite">
                <label for="event-date">Tanggal dan Waktu Acara:</label>
                <input type="date" id="event-date" required>
                <label for="event-location">Tempat/Lokasi Acara:</label>
                <input type="text" id="event-location" required>
                <label for="additional-info">Keterangan Tambahan:</label>
                <textarea id="additional-info" required></textarea>
            </form>
            <form class="form-shipment">
                <label for="recipient-name">Nama Lengkap Penerima:</label>
                <input type="text" id="recipient-name" required>
                <label for="phone-number">No. Telp:</label>
                <input type="tel" id="phone-number" required>
                <label for="address">Alamat Lengkap dan Keterangan:</label>
                <textarea id="address" required></textarea>
                <label for="shipping-method">Pilih Jasa Pengiriman:</label>
                <select id="shipping-method" required>
                <option value="jne">JNE</option>
                <option value="j&t">J&T Express</option>
                <option value="Shopee Express">Shopee Express</option>
                </select>
            </form>
        </div>
    </div>
    <div class="buttons">
        <button type="submit" form="form-invite">Simpan Keranjang</button>
        <button type="submit" form="form-shipment">Bayar Sekarang</button>
    </div>

  <script src="../resources/js/slides.js"></script>
  <script src="../resources/js/burgersidebar.js"></script>
</body>
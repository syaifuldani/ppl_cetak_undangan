<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php");
    exit();
}
require '../config/connection.php';
require '../config/function.php';

$title = "PleeART";
$jenishalaman = "Tambah Barang";
$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responseAddItems = addItemsToProduct($_POST);

    if (isset($responseAddItems['status']) && $responseAddItems['status'] === 'success') {
        $success_message = $responseAddItems['message'];
    } else {
        // If errors exist, handle them
        $errors = $responseAddItems;
    }
}

?>

<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - PleeART</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>


<body>
    <div class="container">

        <?php require "./template/sidebar.php" ?>

        <main class="main-content">
            <header class="header">
                <h2>Tambah Barang</h2>
                <div class="date"><?php echo date('F d, Y'); ?></div>
                <div class="admin-dropdown">
                    <button class="dropdown-toggle">Admin ▼</button>
                    <ul class="dropdown-menu">
                        <li><a href="../profile/profile.php">Profile</a></li>
                        <li><a href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </header>

            <section class="product-detail">
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Nama Produk -->
                    <div class="form-group">
                        <label for="product-name">Nama Produk</label>
                        <input type="text" id="product-name" name="product_name" placeholder="Masukkan nama produk">
                        <span
                            class="error-message"><?= isset($responseAddItems['field']) ? $responseAddItems['field'] : ''; ?></span>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description"
                            placeholder="Masukkan deskripsi produk"></textarea>
                        <span
                            class="error-message"><?= isset($responseAddItems['field']) ? $responseAddItems['field'] : ''; ?></span>
                    </div>

                    <!-- Kategori (Dropdown) -->
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Pernikahan">Undangan Pernikahan</option>
                            <option value="Khitan">Undangan Khitan</option>
                            <option value="Walimatul">Undangan Walimatul</option>
                            <option value="Tahlil&KirimDoa">Undangan Tahlil & Kirim Doa</option>
                            <option value="UlangTahun">Undangan Ulang Tahun</option>
                        </select>
                        <span
                            class="error-message"><?= isset($responseAddItems['category']) ? $responseAddItems['category'] : ''; ?></span>
                    </div>

                    <!-- Harga Produk -->
                    <div class="form-group price-group">
                        <div class="price-field">
                            <label for="product-price">Harga Produk</label>
                            <input type="text" id="product-price" name="product_price"
                                placeholder="Masukkan harga produk">
                        </div>
                        <span
                            class="error-message"><?= isset($responseAddItems['number']) ? $responseAddItems['number'] : ''; ?></span>
                    </div>

                    <!-- Product Gallery -->
                    <div class="product-gallery">
                        <label>Product Gallery (max 3)</label>
                        <div class="image-upload" style="border: #000000 2px solid; margin-top:1rem;">
                            <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                            <input type="file" id="file-upload" name="product_image[]"
                                accept=".jpg,.jpeg,.png,.gif,.webp" multiple onchange="previewImages(event)">
                            <input type="file" id="file-upload" name="product_image[]"
                                accept=".jpg,.jpeg,.png,.gif,.webp" multiple onchange="previewImages(event)">
                            <p>Drop your images here, or browse. Jpeg, png, gif, webp are allowed</p>
                        </div>
                        <span
                            class="error-message"><?= isset($responseAddItems['imageToLarge']) ? $responseAddItems['imageToLarge'] : ''; ?></span>
                        <span
                            class="error-message"><?= isset($responseAddItems['imageNotSupported']) ? $responseAddItems['imageNotSupported'] : ''; ?></span>
                        <span
                            class="error-message"><?= isset($responseAddItems['field']) ? $responseAddItems['field'] : ''; ?></span>
                    </div>

                    <!-- Tombol Submit dan Cancel -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-update">Tambah</button>
                        <button type="button" class="btn btn-cancel"
                            onclick="window.location.href='product.php'">CANCEL</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        function previewImages(event) {
            var files = event.target.files;
            var previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = '';

            Array.from(files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var imgElement = document.createElement('img');
                        imgElement.src = e.target.result;
                        imgElement.style.maxWidth = '150px';
                        imgElement.style.marginBottom = '10px';
                        imgElement.style.border = '1px solid #ccc';
                        previewContainer.appendChild(imgElement);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
    <script>
        <?php if (isset($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $success_message ?>'
            });
        <?php elseif (isset($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $error_message ?>'
            });
        <?php endif; ?>
    </script>
</body>


</html>
<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php");
    exit();
}

$title = "PleeART";
$jenishalaman = "Tambah Barang";
$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../config/connection.php';

    // Alias objek PDO dari $GLOBALS['db'] ke $GLOBALS["db"] untuk kompatibilitas
    

    // Ambil dan sanitasi data dari form
    $nama_produk = trim($_POST['product_name']);
    $deskripsi = trim($_POST['description']);
    $kategori = trim($_POST['category']);
    $harga_product = trim($_POST['product_price']);

    // Daftar kategori yang diizinkan
    $allowed_categories = [
        'undangan Pernikahan',
        'undangan Khitan',
        'undangan Walimatul',
        'undangan Tahlil & Kirim Doa',
        'undangan Ulang Tahun'
    ];

    // Validasi input
    if (empty($nama_produk) || empty($deskripsi) || empty($kategori) || empty($harga_product)) {
        echo "Semua field wajib diisi!";
        exit();
    }

    // Validasi kategori
    if (!in_array($kategori, $allowed_categories)) {
        echo "Kategori tidak valid.";
        exit();
    }

    // Validasi harga_product adalah angka
    if (!is_numeric($harga_product)) {
        echo "Harga Produk harus berupa angka!";
        exit();
    }

    // Handle upload gambar (opsional)
    $gambar_satu = $gambar_dua = $gambar_tiga = null;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Looping untuk memeriksa apakah ada file yang diunggah
    if (isset($_FILES['product_image'])) {
        for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
            if ($_FILES['product_image']['error'][$i] === 0) {
                $file_extension = strtolower(pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($file_extension, $allowed_extensions)) {
                    // Optional: Cek ukuran file (misalnya maksimal 2MB)
                    $max_size = 2 * 1024 * 1024; // 2MB
                    if ($_FILES['product_image']['size'][$i] > $max_size) {
                        echo "Ukuran gambar terlalu besar: " . htmlspecialchars($_FILES['product_image']['name'][$i]);
                        exit();
                    }

                    $image_data = file_get_contents($_FILES['product_image']['tmp_name'][$i]);
                    if ($i == 0) {
                        $gambar_satu = $image_data;
                    } elseif ($i == 1) {
                        $gambar_dua = $image_data;
                    } elseif ($i == 2) {
                        $gambar_tiga = $image_data;
                    }
                } else {
                    echo "Format gambar tidak didukung: " . htmlspecialchars($_FILES['product_image']['name'][$i]);
                    exit();
                }
            }
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO products (nama_produk, deskripsi, harga_product, gambar_satu, gambar_dua, gambar_tiga, kategori) 
            VALUES (:nama_produk, :deskripsi, :harga_product, :gambar_satu, :gambar_dua, :gambar_tiga, :kategori)";
    
    try {
        $stmt = $GLOBALS["db"]->prepare($sql);
        $stmt->bindParam(':nama_produk', $nama_produk, PDO::PARAM_STR);
        $stmt->bindParam(':deskripsi', $deskripsi, PDO::PARAM_STR);
        $stmt->bindParam(':harga_product', $harga_product, PDO::PARAM_STR);
        $stmt->bindParam(':gambar_satu', $gambar_satu, PDO::PARAM_LOB);
        $stmt->bindParam(':gambar_dua', $gambar_dua, PDO::PARAM_LOB);
        $stmt->bindParam(':gambar_tiga', $gambar_tiga, PDO::PARAM_LOB);
        $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect atau tampilkan pesan sukses
        header("Location: product.php?message=Produk berhasil ditambahkan");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
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
                        <input type="text" id="product-name" name="product_name" placeholder="Masukkan nama produk" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" placeholder="Masukkan deskripsi produk" required></textarea>
                    </div>

                    <!-- Kategori (Dropdown) -->
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="undangan Pernikahan">Undangan Pernikahan</option>
                            <option value="undangan Khitan">Undangan Khitan</option>
                            <option value="undangan Walimatul">Undangan Walimatul</option>
                            <option value="undangan Tahlil & Kirim Doa">Undangan Tahlil & Kirim Doa</option>
                            <option value="undangan Ulang Tahun">Undangan Ulang Tahun</option>
                        </select>
                    </div>

                    <!-- Harga Produk -->
                    <div class="form-group price-group">
                        <div class="price-field">
                            <label for="product-price">Harga Produk</label>
                            <input type="text" id="product-price" name="product_price" placeholder="Masukkan harga produk" required>
                        </div>
                    </div>

                    <!-- Product Gallery -->
                    <div class="product-gallery">
                        <label>Product Gallery (max 3)</label>
                        <div class="image-upload" style="border: #000000 2px solid; margin-top:1rem;">
                            <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                            <input type="file" id="file-upload" name="product_image[]" accept=".jpg,.jpeg,.png,.gif,.webp" multiple onchange="previewImages(event)">
                            <p>Drop your images here, or browse. Jpeg, png, gif, webp are allowed</p>
                        </div>
                    </div>

                    <!-- Tombol Submit dan Cancel -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-update">Tambah</button>
                        <button type="button" class="btn btn-cancel" onclick="window.location.href='product.php'">CANCEL</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

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
</body>
</html>

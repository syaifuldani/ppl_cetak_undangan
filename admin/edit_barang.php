<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php");
    exit();
}

require '../config/connection.php';

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE product_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $product_id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['product_name'];
    $deskripsi = $_POST['description'];
    $kategori = $_POST['category'];
    $harga_product = $_POST['product_price'];

    // Handle upload gambar (opsional)
    $gambar_satu = $gambar_dua = $gambar_tiga = null;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
        if (isset($_FILES['product_image']['name'][$i]) && $_FILES['product_image']['error'][$i] == 0) {
            $file_extension = strtolower(pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION));
            if (in_array($file_extension, $allowed_extensions)) {
                $image_data = file_get_contents($_FILES['product_image']['tmp_name'][$i]);
                if ($i == 0) {
                    $gambar_satu = $image_data;
                } elseif ($i == 1) {
                    $gambar_dua = $image_data;
                } elseif ($i == 2) {
                    $gambar_tiga = $image_data;
                }
            } else {
                echo "Format gambar tidak didukung: " . $_FILES['product_image']['name'][$i];
                exit();
            }
        }
    }

    // Update data ke database
    $sql = "UPDATE products SET nama_produk = ?, deskripsi = ?, harga_product = ?, gambar_satu = ?, gambar_dua = ?, gambar_tiga = ?, kategori = ? WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nama_produk,
        $deskripsi,
        $harga_product,
        $gambar_satu ?? $product['gambar_satu'],   // Simpan gambar lama jika tidak ada yang diunggah
        $gambar_dua ?? $product['gambar_dua'],
        $gambar_tiga ?? $product['gambar_tiga'],
        $kategori,
        $product_id
    ]);

    header("Location: product.php?status=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <div class="container">

        <?php require "./template/sidebar.php" ?>

        <main class="main-content">
            <header class="header">
                <h2>Edit Barang</h2>
                <div class="date">Oct 11, 2023 - Nov 11, 2022</div>
                <div class="admin-dropdown">
                    <button class="dropdown-toggle">Admin ▼</button>
                    <ul class="dropdown-menu">
                        <li><a href="../profile/profile.php">Profile</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </div>
            </header>

            <section class="product-detail">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product-name">Nama Produk</label>
                        <input type="text" id="product-name" name="product_name" value="<?php echo $product['nama_produk']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" required><?php echo $product['deskripsi']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <input type="text" id="category" name="category" value="<?php echo $product['kategori']; ?>" required>
                    </div>

                    <div class="form-group price-group">
                        <div class="price-field">
                            <label for="product-price">Harga Produk</label>
                            <input type="text" id="product-price" name="product_price" value="<?php echo $product['harga_product']; ?>" required>
                        </div>
                    </div>

                    <div class="product-gallery">
                        <label>Product Gallery (max 3)</label>
                        <div class="image-upload" style="border: #000000 2px solid; margin-top:1rem;">
                            <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <!-- Tampilkan preview gambar yang sudah ada -->
                                <?php if ($product['gambar_satu']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['gambar_satu']); ?>" style="max-width: 150px; border: 1px solid #ccc;">
                                <?php endif; ?>
                                <?php if ($product['gambar_dua']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['gambar_dua']); ?>" style="max-width: 150px; border: 1px solid #ccc;">
                                <?php endif; ?>
                                <?php if ($product['gambar_tiga']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['gambar_tiga']); ?>" style="max-width: 150px; border: 1px solid #ccc;">
                                <?php endif; ?>
                            </div>
                            <input type="file" id="file-upload" name="product_image[]" accept=".jpg,.jpeg,.png,.gif,.webp" multiple onchange="previewImages(event)">
                            <p>Drop your images here, or browse. Jpeg, png, gif, webp are allowed</p>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-update">Update</button>
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


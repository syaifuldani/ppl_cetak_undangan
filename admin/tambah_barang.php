<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">

        <?php require "./template/sidebar.php" ?>

        <main class="main-content">
            <header class="header">
                <h2>Tambah Barang</h2>
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
                        <input type="text" id="product-name" name="product_name" placeholder="Masukkan nama produk">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description"
                            placeholder="Masukkan deskripsi produk"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <input type="text" id="category" name="category" value="">
                    </div>

                    <div class="form-group">
                        <label for="brand">Nama Brand</label>
                        <input type="text" id="brand" name="brand" value="">
                    </div>

                    <div class="form-group">
                        <label for="stock">Jumlah Stok</label>
                        <input type="number" id="stock" name="stock" value="">
                    </div>

                    <div class="form-group price-group">
                        <div class="price-field">
                            <label for="product-price">Harga Produk</label>
                            <input type="text" id="product-price" name="product_price" value="">
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-update">Tambah</button>
                        <button type="button" class="btn btn-cancel"
                            onclick="window.location.href='product.php'">CANCEL</button>
                    </div>
                </form>

                <div class="product-gallery">
                    <label>Product Gallery</label>
                    <div class="image-upload" style="border: #000000 2px solid; margin-top:1rem;">
                        <!-- Tempat untuk menampilkan gambar preview -->
                        <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                        <input type="file" id="file-upload" name="product_image[]" accept=".jpg,.png" multiple
                            onchange="previewImages(event)">
                        <p>Drop your images here, or browse. Jpeg, png are allowed</p>
                    </div>
                </div>
            </section>

        </main>
    </div>
    <script>
        function previewImages(event) {
            var files = event.target.files;
            var previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = ''; // Kosongkan container sebelum menampilkan gambar baru

            Array.from(files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var imgElement = document.createElement('img');
                        imgElement.src = e.target.result;
                        imgElement.style.maxWidth = '150px'; // Set ukuran maksimal untuk pratinjau gambar
                        imgElement.style.marginBottom = '10px';
                        imgElement.style.border = '1px solid #ccc';
                        previewContainer.appendChild(imgElement); // Tambahkan gambar ke container
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>

</html>
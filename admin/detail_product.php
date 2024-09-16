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
    <div class="sidebar">
            <div class="logo">
                <img src="./style/img/logo.png" alt="Logo">
                <span>PLEE.ART</span>
            </div>
            <ul>
                <li>
                    <button class="dashboard-button">
                    <a href="./dashboard.php">
                        <img src="./style/img/dashboard.png" alt="Logo">
                        <span>Dashboard</span>
                    </a>
                    </button>
                </li>
                <li>
                    <button class="dashboard-button">
                    <a href="product.php">
                        <img src="./style/img/produk.png" alt="Logo">
                        <span>All Product</span>
                    </a>
                    </button>
                </li>   
                <li>
                    <button class="dashboard-button">
                    <a href="./orderlist.php">
                        <img src="./style/img/order.png" alt="Logo">
                        <span>Order List</span>
                    </a>
                    </button>
                </li>
            </ul>
            <div class="categories">
                <h4>Categories</h4>
                <ul>
                    <li><a href="#">Undangan Pernikahan</a></li>
                    <li><a href="#">Undangan Khitan</a></li>
                    <li><a href="#">Undangan Tahlil</a></li>
                </ul>
            </div>
        </div>
        <main class="main-content">
        <header class="header">
            <h2>Dashboard</h2>
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
                <form action="update-product.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product-name">Nama Produk</label>
                        <input type="text" id="product-name" name="product_name" placeholder="Masukkan nama produk">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" placeholder="Masukkan deskripsi produk"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <input type="text" id="category" name="category" value="Sneaker">
                    </div>

                    <div class="form-group">
                        <label for="brand">Nama Brand</label>
                        <input type="text" id="brand" name="brand" value="Addidas">
                    </div>

                    <div class="form-group">
                        <label for="stock">Jumlah Stok</label>
                        <input type="number" id="stock" name="stock" value="211">
                    </div>

                    <div class="form-group price-group">
                        <div class="price-field">
                            <label for="product-price">Harga Produk</label>
                            <input type="text" id="product-price" name="product_price" value="Rp. 10.000">
                        </div>
                        <div class="price-field">
                            <label for="sale-price">Harga Jual</label>
                            <input type="text" id="sale-price" name="sale_price" value="Rp. 20.000">
                        </div>
                    </div>

                    <div class="product-gallery">
                        <label>Product Gallery</label>
                        <div class="image-upload">
                            <img src="product-sample.png" alt="Product Image">
                            <input type="file" id="file-upload" name="product_image" accept=".jpg,.png">
                            <p>Drop your image here, or browse. Jpeg, png are allowed</p>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-update">UPDATE</button>
                        <button type="button" class="btn btn-delete">DELETE</button>
                        <button type="button" class="btn btn-cancel">CANCEL</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>

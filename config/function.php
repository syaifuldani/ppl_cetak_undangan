<?php

require "../config/connection.php";

// ----------------------------------------------------------------
// CUSTOMER FUNCTIONS
// ----------------------------------------------------------------
// CUSTOMER FUNCTIONS

function registerCustomer($data)
{
    $errors = [];

    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $confirm_password = $data['confirm-password'];

    // Validate name
    if (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = 'Nama hanya boleh mengandung huruf dan spasi!';
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email tidak valid!';
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors['email'] = 'Email sudah terdaftar!';
        }
    }

    // Validate password
    if (
        empty($password) || strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[\W_]/", $password)
    ) {
        $errors['password'] = 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol!';
    }

    // Check if password and confirmation match
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Password dan konfirmasi password tidak cocok!';
    }

    // If there are no errors, proceed to save the user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nama_lengkap, email, password) VALUES (:name, :email, :password)";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $GLOBALS['db']->lastInsertId();
            $_SESSION['user_email'] = $email;
            return ['status' => 'success', 'message' => 'Anda berhasil mendaftar!'];
        } else {
            return ['status' => 'errors', 'message' => 'Terjadi kesalahan saat registrasi!'];
        }
    }

    return $errors;
}

function loginCustomer($data)
{
    // Inisialisasi untuk menampung error 
    $errors = [];

    // Ambil nilai dari form
    $email = $data['email'];

    // Validasi email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email tidak valid!';
    }

    // Ambil nilai password
    $password = $data['password'];

    // Lanjutkan hanya jika tidak ada error pada email
    if (empty($errors)) {
        // Query untuk memeriksa user di database
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            // Buat session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['nama_lengkap'];
            $_SESSION['user_profile'] = $user['profile_image'];
            header("Location: dashboard.php"); // Redirect ke halaman dashboard
            exit();
        } else {
            // Jika user tidak ditemukan atau password salah
            $errors['login'] = 'Email atau password salah!';
        }
    }

    // Kembalikan array error jika ada
    return $errors;
}

function updateProfileUser($data)
{
    // Cek apakah data dikirim melalui form
    $name = isset($data['nama_lengkap']) ? $data['nama_lengkap'] : '';
    $alamat = isset($data['alamat']) ? $data['alamat'] : '';
    $nohp = isset($data['nomor_telepon']) ? $data['nomor_telepon'] : '';
    $email = isset($data['email']) ? $data['email'] : '';

    // Dapatkan ID user dari sesi
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    // Jika user_id ada, lakukan update ke database
    if ($user_id > 0) {
        try {
            // Query untuk update data user
            $sql = "UPDATE users SET nama_lengkap = :name, alamat = :alamat, nomor_telepon = :nohp, email = :email WHERE user_id = :user_id";

            // Persiapkan dan eksekusi query
            $stmt = $GLOBALS['db']->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':alamat' => $alamat,
                ':nohp' => $nohp,
                ':email' => $email,
                ':user_id' => $user_id
            ]);

            // Memperbarui data session setelah update
            $_SESSION['user_name'] = $name;
            $_SESSION['alamat'] = $alamat;
            $_SESSION['nomor_telepon'] = $nohp;
            $_SESSION['user_email'] = $email;

            // Memeriksa apakah file diupload
            if (isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = "../customer/uploads/";
                $file_name = basename($_FILES["profile-image"]["name"]);
                $target_file = $target_dir . time() . "_" . $file_name; // Menambahkan timestamp agar nama file unik
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $maxFileSize = 2 * 1024 * 1024; // Maksimum ukuran file 2MB

                // Cek apakah file adalah gambar
                $check = getimagesize($_FILES["profile-image"]["tmp_name"]);
                if ($check !== false) {
                    // Cek ukuran file
                    if ($_FILES["profile-image"]["size"] > $maxFileSize) {
                        return ['status' => false, 'message' => "Maaf, ukuran file terlalu besar. Maksimum ukuran file adalah 2MB."];
                    } else {
                        // Upload file
                        if (move_uploaded_file($_FILES["profile-image"]["tmp_name"], $target_file)) {
                            // Simpan path file gambar ke database
                            $user_id = $_SESSION['user_id'];
                            $sql = "UPDATE users SET profile_image = :profile_image WHERE user_id = :user_id";
                            $stmt = $GLOBALS['db']->prepare($sql);
                            $stmt->execute([
                                ':profile_image' => $target_file, // Menyimpan path file ke database
                                ':user_id' => $user_id
                            ]);

                            // Jika berhasil, tampilkan pesan sukses
                            if ($stmt->rowCount()) {
                                $_SESSION['user_profile'] = $target_file; // Simpan path ke sesi
                                return ['status' => true, 'message' => 'Profil dan gambar berhasil diperbarui!'];
                            } else {
                                return ['status' => false, 'message' => 'Profil diperbarui, tapi tidak ada perubahan pada gambar.'];

                            }
                        } else {
                            return ['status' => false, 'message' => 'Terjadi kesalahan saat mengunggah file.'];
                        }
                    }
                } else {
                    return ['status' => false, 'message' => "File yang diunggah bukan gambar."];
                }
            }

            // Jika update berhasil atau tidak ada baris yang diupdate
            if ($stmt->rowCount() > 0) {
                return ['status' => true, 'message' => 'Profil berhasil diperbarui.'];
            } else {
                return ['status' => false, 'message' => 'Tidak ada perubahan pada profil.'];
            }

        } catch (\Exception $e) {
            // Tangani error database
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }

    } else {
        return ['status' => false, 'message' => 'User ID tidak ditemukan!'];
    }
}

// Function to get products from the database
function getProductData($kategori)
{
    try {
        // SQL query untuk mengambil produk berdasarkan kategori
        $sql = "SELECT product_id, nama_produk, deskripsi, harga_produk, gambar_satu, gambar_dua, gambar_tiga, kategori
                FROM products
                WHERE kategori = :kategori";

        // Prepare the statement
        $stmt = $GLOBALS['db']->prepare($sql);

        // Bind parameter untuk kategori
        $stmt->bindParam(':kategori', $kategori);

        // Execute the query
        $stmt->execute();

        // Ambil hasil sebagai array asosiatif
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Encode gambar dalam format base64
        foreach ($products as &$product) {
            if (!empty($product['gambar_satu'])) {
                $product['gambar_satu'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_satu']);
            }
            if (!empty($product['gambar_dua'])) {
                $product['gambar_dua'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_dua']);
            }
            if (!empty($product['gambar_tiga'])) {
                $product['gambar_tiga'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_tiga']);
            }
        }

        return $products; // Kembalikan array produk
    } catch (PDOException $e) {
        // Tangani error
        return ['error' => 'Error fetching products: ' . $e->getMessage()];
    }
}

function getProductDetails($product_id)
{
    try {
        // Query untuk mengambil detail produk berdasarkan ID
        $sql = "SELECT *
                FROM products
                WHERE product_id = :product_id";

        // Prepare statement
        $stmt = $GLOBALS['db']->prepare($sql);

        // Bind parameter product_id
        $stmt->bindParam(':product_id', $product_id);

        // Execute query
        $stmt->execute();

        // Ambil hasilnya sebagai array asosiatif
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika tidak ada produk yang ditemukan, return error
        if (!$product) {
            return ['error' => 'Produk tidak ditemukan'];
        }

        // Encode gambar menjadi base64
        if (!empty($product['gambar_satu'])) {
            $product['gambar_satu'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_satu']);
        }
        if (!empty($product['gambar_dua'])) {
            $product['gambar_dua'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_dua']);
        } else {
            unset($product['gambar_dua']); // Hapus jika tidak ada
        }
        if (!empty($product['gambar_tiga'])) {
            $product['gambar_tiga'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_tiga']);
        } else {
            unset($product['gambar_tiga']); // Hapus jika tidak ada
        }

        return $product; // Kembalikan data produk

    } catch (PDOException $e) {
        return ['error' => 'Error fetching product: ' . $e->getMessage()];
    }
}

function getRandomProducts($limit = 2)
{
    try {
        // SQL query to get random products
        $sql = "SELECT product_id, nama_produk, deskripsi, harga_produk, gambar_satu 
                FROM products
                ORDER BY RAND()
                LIMIT :limit";

        // Prepare the statement
        $stmt = $GLOBALS['db']->prepare($sql);

        // Bind the limit parameter
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch all the results as associative arrays
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the products and encode the images in base64
        // Encode gambar dalam format base64
        foreach ($products as &$product) {
            if (!empty($product['gambar_satu'])) {
                $product['gambar_satu'] = 'data:image/jpeg;base64,' . base64_encode($product['gambar_satu']);
            }
        }

        return $products;
    } catch (PDOException $e) {
        return ['error' => 'Error fetching products: ' . $e->getMessage()];
    }
}

function addToCart($product_id, $user_id, $quantity = 1, $total_price = 0.00)
{
    // Query untuk menyimpan data ke tabel carts
    $query = "INSERT INTO carts (product_id, user_id, jumlah, total_harga) VALUES (:product_id, :user_id, :jumlah, :total_harga)";

    // Mempersiapkan statement
    $stmt = $GLOBALS['db']->prepare($query);

    // Bind parameter
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':jumlah', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':total_harga', $total_price, PDO::PARAM_STR);

    // Eksekusi statement
    if ($stmt->execute()) {
        return true; // Berhasil
    } else {
        // Jika produk belum ada di keranjang, tambahkan sebagai data baru
        $queryInsert = "INSERT INTO carts (product_id, user_id, jumlah, total_harga) VALUES (:product_id, :user_id, :jumlah, :total_harga)";
        $stmtInsert = $GLOBALS['db']->prepare($queryInsert);
        $stmtInsert->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmtInsert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtInsert->bindParam(':jumlah', $quantity, PDO::PARAM_INT);
        $stmtInsert->bindParam(':total_harga', $total_price, PDO::PARAM_STR);

        return $stmtInsert->execute(); // Berhasil ditambahkan
    }
}

function getCartItems($userId)
{
    // Inisialisasi array untuk menyimpan item keranjang
    $cartItems = [];

    try {
        // Query untuk mengambil data item keranjang dari database
        $sql = "SELECT c.cart_id, p.nama_produk, p.gambar_satu, c.jumlah, p.harga_produk, c.product_id, p.gambar_dua, p.gambar_tiga
                FROM carts c
                JOIN products p ON c.product_id = p.product_id
                WHERE c.user_id = :user_id";

        // Fetch semua item ke dalam array
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        // Fetch semua item ke dalam array
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Encode gambar_satu ke base64 jika ada
            if (!empty($item['gambar_satu'])) {
                $item['gambar_satu'] = 'data:image/jpeg;base64,' . base64_encode($item['gambar_satu']);
            }
            if (!empty($item['gambar_dua'])) {
                $item['gambar_dua'] = 'data:image/jpeg;base64,' . base64_encode($item['gambar_dua']);
            }
            if (!empty($item['gambar_tiga'])) {
                $item['gambar_tiga'] = 'data:image/jpeg;base64,' . base64_encode($item['gambar_tiga']);
            }
            // Masukkan item ke dalam array cartItems
            $cartItems[] = $item;
        }

    } catch (PDOException $e) {
        // Tangani error
        return ['error' => 'Error fetching cart items: ' . $e->getMessage()];
    }

    // Kembalikan array item keranjang
    return $cartItems;
}

function updateCartItem($userId, $quantities)
{
    try {
        if (!isset($userId)) {
            throw new Exception('Anda harus login terlebih dahulu');
        }

        if (empty($quantities) || !is_array($quantities)) {
            throw new Exception('Data keranjang tidak valid');
        }

        $GLOBALS['db']->beginTransaction();

        foreach ($quantities as $productId => $quantity) {
            $quantity = max(1, (int) $quantity);

            $stmt = $GLOBALS['db']->prepare("
                UPDATE carts 
                SET jumlah = :jumlah,
                    total_harga = (SELECT harga_produk FROM products WHERE product_id = :product_id) * :jumlah
                WHERE user_id = :user_id 
                AND product_id = :product_id
            ");

            $stmt->execute([
                ':jumlah' => $quantity,
                ':user_id' => $userId,
                ':product_id' => $productId
            ]);
        }

        $GLOBALS['db']->commit();
        return true;

    } catch (Exception $e) {
        $GLOBALS['db']->rollBack();
        error_log("Cart update error: " . $e->getMessage());
        return false;
    }
}

function deleteCartItems($userId, $cartId)
{
    // Validasi: pastikan $cartId adalah integer
    if (!is_numeric($cartId)) {
        throw new Exception("Invalid cart ID.");
    }

    // Query untuk menghapus item dari keranjang
    $sql = "DELETE FROM carts WHERE user_id = :user_id AND cart_id = :cart_id";
    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Berhasil menghapus
        return true;
    } else {
        // Gagal menghapus
        return false;
    }
}

function searchProducts($searchTerm)
{
    global $db;
    $searchResults = [];

    // Mencari produk berdasarkan nama produk
    $stmt = $db->prepare("SELECT product_id, nama_produk, kategori, gambar_satu FROM products WHERE nama_produk LIKE :searchTerm");
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmt->execute();

    // Ambil hasil pencarian
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cek apakah request adalah AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        // Jika AJAX, kirimkan hasil pencarian dalam format HTML
        if (!empty($searchResults)) {
            foreach ($searchResults as $product) {
                // Mengonversi gambar biner menjadi format base64
                $base64Image = base64_encode($product['gambar_satu']);
                echo '<a href="productdetail.php?id=' . $product['product_id'] . '" class="search-item">';
                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Produk">';
                echo '<label>';
                echo '<p>' . $product['nama_produk'] . '</p>';
                echo '<p class="kategori"> Kategori Undangan : ' . $product['kategori'] . '</p>';
                echo '</label>';
                echo '</a>';
            }
        } else {
            echo '<p>Undangan Tidak Ditemukan</p>';
        }
        exit;
    }

    return $searchResults;
}


// END CUSTOMER FUNCTIONS

// ----------------------------------------------------------------

// ADMIN FUNCTIONS

function registrasiAdmin($data)
{
    $errors = [];

    // Ambil data dari form dan bersihkan input
    $name = trim($data['name']);
    $email = trim($data['email']);
    $password = $data['password'];
    $confirm_password = $data['confirm-password'];

    // Validate name
    if (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = 'Nama hanya boleh mengandung huruf dan spasi!';
    }

    // Validate password
    if (
        empty($password) || strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[\W_]/", $password)
    ) {
        $errors['password'] = 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol!';
    }

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Password dan konfirmasi password tidak cocok!';
    }

    try {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid!';
        } else {
            // Check if email already exists
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $GLOBALS['db']->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors['email'] = 'Email sudah terdaftar!';
            }
        }

        if (empty($errors)) {
            $jenis_pengguna = 'admin';
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (nama_lengkap, email, password, jenis_pengguna) VALUES (:name, :email, :password,:jenis_pengguna)";
            $stmt = $GLOBALS['db']->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':jenis_pengguna', $jenis_pengguna, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login_admin.php"); // Redirect ke halaman login admin
                exit();
            } else {
                return ['status' => 'errors', 'message' => 'Terjadi kesalahan saat registrasi!'];
            }
        }
    } catch (PDOException $e) {
        // Tangani error database
        $_SESSION['error'] = "Terjadi kesalahan pada server: " . $e->getMessage();
        header("Location: ini_register_admin_loh.php");
        exit();
    }
    return $errors;
}

function loginAdmin($data)
{
    $errors = [];
    // Ambil nilai dari form dan bersihkan input
    $email = trim($data['email']);
    $password = $data['password'];

    // Validasi input
    if (empty($email) || empty($password)) {
        $errors['email'] = 'Email wajib diisi!';
    }

    // Validasi email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email tidak valid!';
    }

    try {
        $jenis_pengguna = 'admin';

        // Query untuk memeriksa user di database dan memastikan jenis_pengguna adalah 'admin'
        $sql = "SELECT * FROM users WHERE email = :email AND jenis_pengguna = :jenis_pengguna";
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':jenis_pengguna', $jenis_pengguna, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password']) && $jenis_pengguna === 'admin') {
            // Buat session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['nama_lengkap'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nope'] = $user['nomor_telepon'];
            $_SESSION['jenis_pengguna'] = $user['jenis_pengguna'];
            header("Location: dashboard.php"); // Redirect ke halaman dashboard admin
            exit();
        } else {
            // Simpan pesan error dalam session
            $errors['login'] = 'Email atau password salah!';
        }
    } catch (PDOException $e) {
        // Tangani error database
        $_SESSION['error'] = "Terjadi kesalahan pada server: " . $e->getMessage();
        header("Location: login_admin.php");
        exit();
    }

    return $errors;
}

function updateProfileAdmin($data)
{
    // Cek apakah data dikirim melalui form
    $name = isset($data['nama_lengkap']) ? $data['nama_lengkap'] : '';
    $alamat = isset($data['alamat']) ? $data['alamat'] : '';
    $nohp = isset($data['nomor_telepon']) ? $data['nomor_telepon'] : '';
    $email = isset($data['email']) ? $data['email'] : '';

    // Dapatkan ID user dari sesi
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    // Jika user_id ada, lakukan update ke database
    if ($user_id > 0) {
        try {
            // Query untuk update data user
            $sql = "UPDATE users SET nama_lengkap = :name, alamat = :alamat, nomor_telepon = :nohp, email = :email WHERE user_id = :user_id";

            // Persiapkan dan eksekusi query
            $stmt = $GLOBALS['db']->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':alamat' => $alamat,
                ':nohp' => $nohp,
                ':email' => $email,
                ':user_id' => $user_id
            ]);

            // Memperbarui data session setelah update
            $_SESSION['user_name'] = $name;
            $_SESSION['alamat'] = $alamat;
            $_SESSION['nomor_telepon'] = $nohp;
            $_SESSION['user_email'] = $email;

            // Memeriksa apakah file diupload
            if (isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = "../customer/uploads/";
                $file_name = basename($_FILES["profile-image"]["name"]);
                $target_file = $target_dir . time() . "_" . $file_name; // Menambahkan timestamp agar nama file unik
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $maxFileSize = 2 * 1024 * 1024; // Maksimum ukuran file 2MB

                // Cek apakah file adalah gambar
                $check = getimagesize($_FILES["profile-image"]["tmp_name"]);
                if ($check !== false) {
                    // Cek ukuran file
                    if ($_FILES["profile-image"]["size"] > $maxFileSize) {
                        return ['status' => false, 'message' => "Maaf, ukuran file terlalu besar. Maksimum ukuran file adalah 2MB."];
                    } else {
                        // Upload file
                        if (move_uploaded_file($_FILES["profile-image"]["tmp_name"], $target_file)) {
                            // Simpan path file gambar ke database
                            $user_id = $_SESSION['user_id'];
                            $sql = "UPDATE users SET profile_image = :profile_image WHERE user_id = :user_id";
                            $stmt = $GLOBALS['db']->prepare($sql);
                            $stmt->execute([
                                ':profile_image' => $target_file, // Menyimpan path file ke database
                                ':user_id' => $user_id
                            ]);

                            // Jika berhasil, tampilkan pesan sukses
                            if ($stmt->rowCount()) {
                                $_SESSION['user_profile'] = $target_file; // Simpan path ke sesi
                                return ['status' => true, 'message' => 'Profil dan gambar berhasil diperbarui!'];
                            } else {
                                return ['status' => false, 'message' => 'Profil diperbarui, tapi tidak ada perubahan pada gambar.'];

                            }
                        } else {
                            return ['status' => false, 'message' => 'Terjadi kesalahan saat mengunggah file.'];
                        }
                    }
                } else {
                    return ['status' => false, 'message' => "File yang diunggah bukan gambar."];
                }
            }

            // Jika update berhasil atau tidak ada baris yang diupdate
            if ($stmt->rowCount() > 0) {
                return ['status' => true, 'message' => 'Profil berhasil diperbarui.'];
            } else {
                return ['status' => false, 'message' => 'Tidak ada perubahan pada profil.'];
            }

        } catch (\Exception $e) {
            // Tangani error database
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }

    } else {
        return ['status' => false, 'message' => 'User ID tidak ditemukan!'];
    }
}

function addItemsToProduct($data)
{
    $errors = [];

    // Ambil dan sanitasi data dari form
    $nama_produk = trim($data['product_name']);
    $deskripsi = trim($data['description']);
    $kategori = isset($data['category']) ? trim($data['category']) : '';
    $harga_product = trim($data['product_price']);

    // Daftar kategori yang diizinkan
    $allowed_categories = [
        'Pernikahan',
        'Khitan',
        'Walimatul',
        'Tahlil&KirimDoa',
        'UlangTahun'
    ];

    // Validasi input
    if (empty($nama_produk) || empty($deskripsi) || empty($kategori) || empty($harga_product)) {
        $errors['field'] = 'Semua field wajib diisi!';
    }

    // Validasi kategori
    if (!in_array($kategori, $allowed_categories)) {
        $errors['category'] = 'Kategori tidak valid!';
    }

    // Validasi harga_product adalah angka
    if (!is_numeric($harga_product)) {
        $errors['number'] = 'Harga Produk harus berupa angka!';
    }

    // Handle upload gambar
    $gambar_satu = $gambar_dua = $gambar_tiga = null;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $max_size = 2 * 1024 * 1024; // Maksimal 2MB per file

    // Gambar Satu
    if (isset($_FILES['gambar_satu']) && $_FILES['gambar_satu']['error'] === 0) {
        $gambar_satu = handleImageUpload($_FILES['gambar_satu'], $allowed_extensions, $max_size, $errors, 'gambar_satu');
    }

    // Gambar Dua
    if (isset($_FILES['gambar_dua']) && $_FILES['gambar_dua']['error'] === 0) {
        $gambar_dua = handleImageUpload($_FILES['gambar_dua'], $allowed_extensions, $max_size, $errors, 'gambar_dua');
    }

    // Gambar Tiga
    if (isset($_FILES['gambar_tiga']) && $_FILES['gambar_tiga']['error'] === 0) {
        $gambar_tiga = handleImageUpload($_FILES['gambar_tiga'], $allowed_extensions, $max_size, $errors, 'gambar_tiga');
    }

    // Jika ada error pada proses upload gambar, hentikan eksekusi
    if (!empty($errors)) {
        return $errors;
    }

    // Simpan data ke database
    $sql = "INSERT INTO products (nama_produk, deskripsi, harga_produk, gambar_satu, gambar_dua, gambar_tiga, kategori) 
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
        return ['status' => 'success', 'message' => 'Berhasil menambah barang!'];
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }

    return $errors;
}

/**
 * Fungsi untuk menangani upload gambar
 */
function handleImageUpload($file, $allowed_extensions, $max_size, &$errors, $field_name)
{
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validasi ekstensi
    if (!in_array($file_extension, $allowed_extensions)) {
        $errors['imageNotSupported'] = "Format gambar tidak didukung: " . htmlspecialchars($file['name']);
        return null;
    }

    // Validasi ukuran file
    if ($file['size'] > $max_size) {
        $errors['imageToLarge'] = "Ukuran gambar terlalu besar: " . htmlspecialchars($file['name']);
        return null;
    }

    // Baca isi file
    return file_get_contents($file['tmp_name']);
}



function getAllDataByCategory($category)
{
    // Ambil data produk undangan khitan dari database
    $sql = "SELECT product_id, nama_produk, deskripsi, harga_produk, gambar_satu, gambar_dua, gambar_tiga, kategori FROM products WHERE kategori = :category";
    $stmt = $GLOBALS["db"]->prepare($sql);
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

// Pesanan saya
function getStatusLabel($status)
{
    $labels = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Sedang Dikemas',
        'shipped' => 'Dalam Pengiriman',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    ];

    return $labels[$status] ?? $status;
}

function getOrdersByID($userId)
{
    global $db;

    // Query utama untuk orders
    $sql = "SELECT o.* FROM orders o 
            WHERE o.user_id = :user_id 
            ORDER BY o.created_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query tambahan untuk mendapatkan items per order
    foreach ($orders as &$order) {
        $itemSql = "SELECT 
                    od.product_id,
                    p.nama_produk,
                    p.gambar_satu,
                    od.jumlah_order,
                    od.harga_order
                FROM order_details od
                JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = :order_id";

        $itemStmt = $db->prepare($itemSql);
        $itemStmt->execute([':order_id' => $order['order_id']]);
        $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $orders;
}

function getOrderList($limit, $offset)
{
    $sql = "SELECT o.order_id, o.created_at, o.transaction_status, o.total_harga, u.nama_lengkap
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        LIMIT :limit OFFSET :offset";
    $stmt = $GLOBALS["db"]->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateStatusByOrderId($orderID, $newStatus)
{
    try {
        global $db;

        // Validasi input
        if (empty($orderID) || empty($newStatus)) {
            throw new Exception("Data tidak lengkap");
        }

        // Validasi status yang diperbolehkan
        $allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $allowed_statuses)) {
            throw new Exception("Status tidak valid");
        }

        // Update status di database
        $stmt = $db->prepare("
            UPDATE orders 
            SET transaction_status = :new_status, 
                updated_at = NOW()
            WHERE order_id = :order_id
        ");

        $stmt->bindParam(':new_status', $newStatus);
        $stmt->bindParam(':order_id', $orderID);

        if (!$stmt->execute()) {
            throw new Exception("Gagal mengupdate status order");
        }

        return [
            'status' => 'success',
            'message' => "Status berhasil diupdate menjadi $newStatus"
        ];

    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function updateResi($orderID, $nomor_resi)
{
    try {
        // Validasi input
        if (empty($orderID) || empty($nomor_resi)) {
            throw new Exception("Order ID dan nomor resi tidak boleh kosong");
        }

        // Cek apakah order exist
        $checkOrder = "SELECT order_id FROM shipments WHERE order_id = :order_id";
        $checkStmt = $GLOBALS['db']->prepare($checkOrder);
        $checkStmt->execute([':order_id' => $orderID]);

        if ($checkStmt->fetch()) {
            // Update jika data sudah ada
            $sql = "UPDATE shipments SET nomor_resi = :nomor_resi WHERE order_id = :order_id";
        } else {
            // Insert jika data belum ada
            $sql = "INSERT INTO shipments (order_id, nomor_resi) VALUES (:order_id, :nomor_resi)";
        }

        // Update nomor resi
        $stmt = $GLOBALS['db']->prepare($sql);
        $result = $stmt->execute([
            ':nomor_resi' => $nomor_resi,
            ':order_id' => $orderID
        ]);

        if (!$result) {
            throw new Exception("Gagal mengupdate nomor resi");
        }

        return [
            'success' => true,
            'message' => 'Nomor resi berhasil diupdate'
        ];
    } catch (PDOException $e) {
        echo "<script>
        alert('Error: Gagal mengupdate nomor resi - " . $e->getMessage() . "');
        window.history.back();
    </script>";
        exit;
    }

}
// ADMIN FUNCTIONS DASHBOARD
function getPenjualanChart()
{
    // Gunakan koneksi PDO yang sudah ada
    global $db;

    // Query untuk mengambil data total penjualan per bulan
    $sql = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS bulan, 
            SUM(jumlah_order) AS total
        FROM 
            order_details
        GROUP BY 
            bulan
        ORDER BY 
            bulan ASC
    ";

    // Menjalankan query dan menangani kemungkinan error
    try {
        // Prepare dan eksekusi query
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // Mengambil hasil query dalam bentuk array asosiatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Tangani error jika query gagal
        die("Query gagal: " . $e->getMessage());
    }

    return $data;
}



// Fungsi untuk menghitung jumlah pemesanan
function getTotalPemesanan()
{
    global $db;
    $sqlPemesanan = "SELECT COUNT(DISTINCT order_id) AS total_pemesanan FROM order_details";
    $stmt = $db->prepare($sqlPemesanan);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_pemesanan'] ?? 0;
}

// Fungsi untuk menghitung total penjualan selesai
function getTotalPenjualanSelesai()
{
    global $db;
    $sqlPenjualanSelesai = "SELECT COUNT(*) AS total_penjualan_selesai FROM orders WHERE transaction_status = 'delivered'";
    $stmt = $db->prepare($sqlPenjualanSelesai);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_penjualan_selesai'] ?? 0;
}

// Fungsi untuk mengambil data penjualan per bulan
function getPenjualanPerBulan()
{
    global $db;
    $sqlPenjualan = "SELECT MONTH(transaction_time) AS bulan, SUM(gross_amount) AS total_penjualan
                     FROM payments
                     GROUP BY MONTH(transaction_time)
                     ORDER BY bulan ASC";
    $stmt = $db->prepare($sqlPenjualan);
    $stmt->execute();
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

// Fungsi untuk mendapatkan penjualan terbanyak
function getPenjualanTerbanyak()
{
    global $db;
    $sqlPenjualanTerbanyak = "SELECT 
        p.nama_produk AS nama_produk,
        SUM(od.jumlah_order) AS jumlah_terjual
    FROM 
        order_details od
    JOIN 
        products p ON od.product_id = p.product_id
    GROUP BY 
        od.product_id
    ORDER BY 
        jumlah_terjual DESC
    LIMIT 5";
    $stmt = $db->prepare($sqlPenjualanTerbanyak);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan pesanan terbaru
function getPesananTerbaru()
{
    global $db;
    $sqlPesananTerbaru = "SELECT 
        o.nama_penerima,
        o.nomor_penerima,
        o.alamat_penerima,
        o.kodepos, 
        o.keterangan_order,
        o.payment_type,
        o.total_harga,
        o.transaction_status
    FROM orders o
    ORDER BY o.created_at DESC";
    $stmt = $db->prepare($sqlPesananTerbaru);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =================================================================



// END ADMIN FUNCTIONS
// ----------------------------------------------------------------
// END FUNCTIONS
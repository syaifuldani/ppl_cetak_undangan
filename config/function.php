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
    // Query untuk memeriksa apakah produk sudah ada di keranjang
    $queryCheck = "SELECT jumlah FROM carts WHERE product_id = :product_id AND user_id = :user_id";
    $stmtCheck = $GLOBALS['db']->prepare($queryCheck);
    $stmtCheck->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmtCheck->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtCheck->execute();
    
    // Jika produk sudah ada di keranjang, update kuantitasnya
    if ($row = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $newQuantity = $row['jumlah'] + $quantity;
        $newTotalPrice = $total_price * $newQuantity; // Total harga disesuaikan
        
        $queryUpdate = "UPDATE carts SET jumlah = :jumlah, total_harga = :total_harga WHERE product_id = :product_id AND user_id = :user_id";
        $stmtUpdate = $GLOBALS['db']->prepare($queryUpdate);
        $stmtUpdate->bindParam(':jumlah', $newQuantity, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':total_harga', $newTotalPrice, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        return $stmtUpdate->execute(); // Berhasil di-update
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
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Encode gambar_satu ke base64 jika ada
        if ($item['gambar_satu']) {
            $item['gambar_satu'] = 'data:image/jpeg;base64,' . base64_encode($item['gambar_satu']);
        }
        $cartItems[] = $item;
    }
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



function updateCartItem($userId, $productId, $quantity)
{
    // Ambil harga produk untuk menghitung total harga
    $sql = "SELECT harga_produk FROM products WHERE product_id = :product_id";
    $stmt = $GLOBALS['db']->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $hargaProduk = $product['harga_produk'];
        $totalHarga = $hargaProduk * $quantity;

        // Jika kuantitas <= 0, hapus item dari keranjang
        if ($quantity <= 0) {
            $sqlDelete = "DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id";
            $stmtDelete = $GLOBALS['db']->prepare($sqlDelete);
            $stmtDelete->bindParam(':user_id', $userId);
            $stmtDelete->bindParam(':product_id', $productId);
            $stmtDelete->execute();
        } else {
            // Update kuantitas dan total harga di tabel carts
            $sqlUpdate = "UPDATE carts SET jumlah = :jumlah, total_harga = :total_harga WHERE user_id = :user_id AND product_id = :product_id";
            $stmtUpdate = $GLOBALS['db']->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':jumlah', $quantity);
            $stmtUpdate->bindParam(':total_harga', $totalHarga);
            $stmtUpdate->bindParam(':user_id', $userId);
            $stmtUpdate->bindParam(':product_id', $productId);
            $stmtUpdate->execute();
        }
    } else {
        echo "Produk tidak ditemukan.";
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
        $errors['category'] = 'Kategori wajib diisi!';
    }

    // Validasi harga_product adalah angka
    if (!is_numeric($harga_product)) {
        $errors['number'] = 'Harga Produk harus berupa angka!';
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
                        $errors['imageToLarge'] = "Ukuran gambar terlalu besar: " . htmlspecialchars($_FILES['product_image']['name'][$i]);
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
                    $errors['imageNotSupported'] = "Format gambar tidak didukung: " . htmlspecialchars($_FILES['product_image']['name'][$i]);
                }
            }
        }
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

function getAllDataByCategory($category)
{
    // Ambil data produk undangan khitan dari database
    $sql = "SELECT product_id, nama_produk, deskripsi, harga_produk, gambar_satu, gambar_dua, gambar_tiga, kategori FROM products WHERE kategori = :kategori";
    $stmt = $GLOBALS["db"]->prepare($sql);
    $stmt->execute(['kategori' => $category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// END ADMIN FUNCTIONS
// ----------------------------------------------------------------
// END FUNCTIONS

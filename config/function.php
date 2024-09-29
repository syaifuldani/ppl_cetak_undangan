<?php
require "../config/connection.php";

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

// CUSTOMER

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

// END CUSTOMER

// ADMIN
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
            $_SESSION['user_email'] = $user['email'];
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
require "../config/connection.php";

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

// CUSTOMER

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

// END CUSTOMER

// ADMIN
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
    $email = trim($_POST['email']);
    $password = $_POST['password'];

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
            $_SESSION['user_email'] = $user['email'];
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
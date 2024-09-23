<?php
$host = "localhost";    // Host server database (biasanya "localhost" jika server database ada di mesin yang sama)
$user = "root";         // Username database MySQL
$password = "";         // Password MySQL (biasanya kosong untuk localhost)
$database = "pleeartDB"; // Nama database

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jika koneksi berhasil
echo "Koneksi ke database berhasil!";
?>


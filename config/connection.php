<?php
$username = 'root';
$password = '';

try {
    $GLOBALS["db"] = new PDO("mysql:host=localhost;dbname=pleeartdb", $username, $password);
    $GLOBALS["db"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Berhasil terhubung ke database";
} catch (PDOException $e) {
    echo "Koneksi Gagal: Error ==>" . $e->getMessage();
}
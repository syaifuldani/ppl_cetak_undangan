<?php
// config.php
$host = 'localhost';
$dbname = 'pleeartdb';
$username = 'root';  // Atur sesuai dengan konfigurasi database Anda
$password = '';      // Atur sesuai dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
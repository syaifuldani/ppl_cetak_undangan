<?php

// config.php
$host = 'localhost';
$dbname = 'pleeartdb';
$username = 'root';  // Atur sesuai dengan konfigurasi database Anda
$password = '';      // Atur sesuai dengan password database Anda

try {
    $GLOBALS['db'] = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode ke exception
    $GLOBALS['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// <?php

// // config.php
// // $host = 'pleeart.my.id';
// $host = 'localhost';
// $dbname = 'sxtnzocx_pleeartdb';
// $username = 'sxtnzocx_adminpleeart';  // Atur sesuai dengan konfigurasi database Anda
// $password = 'notdans123@';      // Atur sesuai dengan password database Anda

// try {
//     $GLOBALS['db'] = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//     // Set error mode ke exception
//     $GLOBALS['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Koneksi gagal: " . $e->getMessage());
// }
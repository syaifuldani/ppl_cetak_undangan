<?php

session_start();
require_once '../config/connection.php'; // Sesuaikan dengan lokasi file database Anda
require_once '../config/midtrans_config.php'; // File yang berisi fungsi payment_handled

try {
    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Anda harus login terlebih dahulu');
    }

    // Validasi input
    $required_fields = ['namapenerima', 'notelppenerima', 'alamatpenerima'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field $field wajib diisi");
        }
    }

    // Proses pembayaran
    $snap_token = payment_handled($_POST, $_SESSION['user_id']);

    // Return snap token
    echo $snap_token;

} catch (Exception $e) {
    http_response_code(400);
    echo $e->getMessage();
}
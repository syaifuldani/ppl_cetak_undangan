<?php
require_once '../config/rajaOngkir.php';

header('Content-Type: application/json');

try {
    if (
        !isset($_POST['destination']) ||
        !isset($_POST['weight']) ||
        !isset($_POST['courier'])
    ) {
        throw new Exception('Incomplete data');
    }

    $rajaOngkir = new RajaOngkir();

    // Tidak perlu origin dari POST lagi karena sudah fix
    $result = $rajaOngkir->calculateShipping(
        $_POST['destination'],
        $_POST['weight'],
        $_POST['courier']
    );

    // Tambahkan info origin ke response
    $result['origin'] = $rajaOngkir->getOriginInfo();

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
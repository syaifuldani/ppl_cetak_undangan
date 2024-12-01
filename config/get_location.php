<?php

require_once '../config/rajaOngkir.php';

header('Content-Type: application/json');

try {
    $rajaOngkir = new RajaOngkir();
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'provinces':
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: 9dbeae2fb67ac68be056d572d047c462
"  // Ganti dengan API key Anda
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("cURL Error #:" . $err);
            }

            echo $response;
            break;

        case 'cities':
            $provinceId = $_GET['province_id'] ?? '';
            if (!$provinceId) {
                throw new Exception('Province ID required');
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $provinceId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: 9dbeae2fb67ac68be056d572d047c462
"  // Ganti dengan API key Anda
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new Exception("cURL Error #:" . $err);
            }

            echo $response;
            break;

        default:
            throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
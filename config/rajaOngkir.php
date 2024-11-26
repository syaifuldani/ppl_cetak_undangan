<?php

require_once '../config/connection.php';
require_once '../config/WeightCalculator.php'; // Class untuk kalkulasi berat
header('Content-Type: application/json');

class RajaOngkir
{
    private $apiKey = "9dbeae2fb67ac68be056d572d047c462";
    private $baseUrl = "https://api.rajaongkir.com/starter/";

    // Tambahkan konstanta untuk origin
    private const ORIGIN_CITY = '289'; // Kabupaten Mojokerto
    private const ORIGIN_CITY_NAME = 'Kabupaten Mojokerto';
    private const ORIGIN_PROVINCE = '11'; // Jawa Timur

    // Fungsi untuk get origin info
    public function getOriginInfo()
    {
        return [
            'city_id' => self::ORIGIN_CITY,
            'city_name' => self::ORIGIN_CITY_NAME,
            'province_id' => self::ORIGIN_PROVINCE
        ];
    }

    // Fungsi untuk mendapatkan semua provinsi
    public function getProvinces()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . "province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ["error" => $err];
        }

        return json_decode($response, true);
    }

    // Fungsi untuk mendapatkan kota berdasarkan province_id
    public function getCities($provinceId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . "city?province=" . $provinceId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ["error" => $err];
        }

        return json_decode($response, true);
    }



    // Fungsi untuk menghitung ongkir
    public function calculateShipping($destination, $courier, $weight)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . "cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>
                "origin=" . self::ORIGIN_CITY .
                "&destination=" . $destination .
                "&weight=" . $weight .
                "&courier=" . $courier,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->apiKey
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        }

        return json_decode($response, true);
    }

}
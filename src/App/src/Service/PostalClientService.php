<?php

declare(strict_types=1);

namespace App\Service;

use function count;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt_array;
use function getenv;
use function http_build_query;
use function json_decode;

use const CURL_HTTP_VERSION_1_1;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_ENCODING;
use const CURLOPT_FOLLOWLOCATION;
use const CURLOPT_HTTP_VERSION;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_MAXREDIRS;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_TIMEOUT;
use const CURLOPT_URL;

class PostalClientService implements PostalClientServiceInterface
{
    public function getAddress(string $address): array
    {
        $host = getenv('ARCGIS_GATEWAY_HOST');
        $port = (int) getenv('ARCGIS_GATEWAY_PORT');

        $url = "http://$host:$port";

        $fields["address"] = $address;

        return $this->request($url, 'POST', $fields);
    }

    private function request(
        string $url,
        string $method,
        array $fields = [],
        array $header = [
            'Content-Type: application/x-www-form-urlencoded',
        ]
    ): array {
        $curl = curl_init();

        $optionsUrl = $url;

        if ($method === 'GET' && count($fields) > 0) {
            $optionsUrl .= '?' . http_build_query($fields);
        }

        $curlOptions = [
            CURLOPT_URL            => $optionsUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $header,
        ];

        if ($method === 'POST' && count($fields) > 0) {
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($fields);
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);

        curl_close($curl);

        try {
            return json_decode($response, true);
        } catch (Exception $e) {
        }

        return [];
    }
}

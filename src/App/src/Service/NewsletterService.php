<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class NewsletterService implements NewsletterServiceInterface
{
    private string $url = '';

    public function __construct(
        array $config
    ) {
        $this->url = $config['app']['newsletter']['url'];
    }

    public function subscribe(string $cid, string $email): void
    {
        $client = new Client([
            'verify' => false
        ]);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $options = [
            'form_params' => [
                'cid'   => $cid,
                'email' => $email,
            ]
        ];

        $request = new Request('POST', $this->url, $headers);

        $client->sendAsync($request, $options)->wait();
    }
}

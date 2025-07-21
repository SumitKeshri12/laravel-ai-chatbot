<?php

namespace App\Services;

use GuzzleHttp\Client;

class RasaService
{
    protected string $endpoint;
    protected Client $client;

    public function __construct()
    {
        $this->endpoint = 'http://localhost:5005/webhooks/rest/webhook';
        $this->client = new Client();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function sendMessage(string $userId, string $message): array
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'sender' => $userId,
                'message' => $message,
            ],
            'http_errors' => false,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}

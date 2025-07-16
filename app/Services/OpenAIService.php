<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected string $endpoint;
    protected Client $client;
    protected string $apiKey;

    public function __construct()
    {
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
        $this->client = new Client();
        $this->apiKey = config('services.openai.api_key');
    }

    public function sendMessage(string $userMessage): string
    {
        try {
            $response = $this->client->post($this->endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'max_tokens' => 256,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $answer = '';
            if (isset($data['choices']) && is_array($data['choices'])) {
                foreach ($data['choices'] as $choice) {
                    if (isset($choice['message']['content'])) {
                        $answer .= $choice['message']['content'];
                    }
                }
            }
            return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? json_decode($response->getBody()->getContents(), true) : null;
            $errorMsg = $body['error']['message'] ?? $e->getMessage();
            return "OpenAI error: " . $errorMsg;
        } catch (\Exception $e) {
            return "OpenAI error: " . $e->getMessage();
        }
    }
}

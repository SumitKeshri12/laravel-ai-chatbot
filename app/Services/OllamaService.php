<?php

namespace App\Services;

use GuzzleHttp\Client;

class OllamaService
{
    protected string $endpoint;
    protected Client $client;

    public function __construct()
    {
        $this->endpoint = 'http://localhost:11434/api/chat';
        $this->client = new Client();
    }

    public function sendMessage(string $userMessage, string $model = 'llama2'): string
    {
        ini_set('max_execution_time', config('app.max_execution_time'));
        $endpoint = 'http://localhost:11434/api/generate';

        $response = $this->client->post($endpoint, [
            'json' => [
                'model' => $model,
                'prompt' => $userMessage,
            ],
            'stream' => true,
            'timeout' => 65,
        ]);

        $body = $response->getBody();
        $answer = '';
        $buffer = '';
        $start = time();

        $logFile = storage_path('logs/ollama_debug.log');
        file_put_contents($logFile, "---- NEW REQUEST ----\n", FILE_APPEND);

        while (!$body->eof()) {
            if (time() - $start > 60) {
                file_put_contents($logFile, "TIMEOUT\n", FILE_APPEND);
                break;
            }
            $buffer .= $body->read(1024);
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 1);
                $line = trim($line);
                if ($line !== '') {
                    file_put_contents($logFile, "LINE: [$line]\n", FILE_APPEND);
                    $json = json_decode($line, true);
                    file_put_contents($logFile, "JSON: " . print_r($json, true) . "\n", FILE_APPEND);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        if (is_array($json) && isset($json[0]) && is_array($json[0])) {
                            foreach ($json as $item) {
                                if (isset($item['response'])) {
                                    $answer .= $item['response'];
                                }
                                if (isset($item['done']) && $item['done'] === true) {
                                    return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
                                }
                            }
                        } elseif (is_array($json)) {
                            if (isset($json['response'])) {
                                $answer .= $json['response'];
                            }
                            if (isset($json['done']) && $json['done'] === true) {
                                return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
                            }
                        }
                    }
                }
            }
        }

        $line = trim($buffer);
        if ($line !== '') {
            $json = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($json) && isset($json[0]) && is_array($json[0])) {
                    foreach ($json as $item) {
                        if (isset($item['response'])) {
                            $answer .= $item['response'];
                        }
                        if (isset($item['done']) && $item['done'] === true) {
                            return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
                        }
                    }
                } elseif (is_array($json)) {
                    if (isset($json['response'])) {
                        $answer .= $json['response'];
                    }
                    if (isset($json['done']) && $json['done'] === true) {
                        return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
                    }
                }
            }
        }

        return $answer !== '' ? $answer : 'Sorry, I did not understand that.';
    }
}

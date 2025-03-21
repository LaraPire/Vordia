<?php

namespace Rayiumir\Vordia\Http\Channels\Drivers\Smsir;

use Illuminate\Support\Facades\Http;
use Rayiumir\Vordia\Http\Channels\Drivers\SmsDriverInterface;
use RuntimeException;

class Ghasedak implements SmsDriverInterface
{
    private string $apiKey;
    private string $template;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->template = $config['template'] ?? '';

        if (empty($this->apiKey) || empty($this->template)) {
            throw new \InvalidArgumentException('Ghasedak configuration is invalid.');
        }
    }

    public function send(string $receptor, array $parameters): void
    {
        $code = $parameters['code'] ?? '';

        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post('https://api.ghasedak.me/v2/verification/send/simple', [
            'receptor' => $receptor,
            'type' => '1', // 1 for template-based SMS
            'template' => $this->template,
            'param1' => $code, // Ghasedak supports up to 3 parameters (param1, param2, param3)
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Ghasedak error: ' . $response->body());
        }
    }
}

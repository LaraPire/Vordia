<?php

namespace Rayiumir\Vordia\Http\Channels\Drivers\Smsir;

use Illuminate\Support\Facades\Http;
use Rayiumir\Vordia\Http\Channels\Drivers\SmsDriverInterface;
use RuntimeException;

class Smsir implements SmsDriverInterface
{
    private string $apiKey;
    private string $templateId;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->templateId = $config['template_id'] ?? '';

        if (empty($this->apiKey) || empty($this->templateId)) {
            throw new \InvalidArgumentException('SMSIR configuration is invalid.');
        }
    }

    public function send(string $receptor, array $parameters): void
    {
        $code = $parameters['code'] ?? '';

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.sms.ir/v1/send/verify', [
            'mobile' => $receptor,
            'templateId' => $this->templateId,
            'parameters' => [
                ['name' => 'Code', 'value' => $code],
            ],
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('SMSIR error: ' . $response->body());
        }
    }
}

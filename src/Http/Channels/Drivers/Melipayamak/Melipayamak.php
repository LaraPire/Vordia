<?php

namespace Rayiumir\Vordia\Http\Channels\Drivers\Melipayamak;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Rayiumir\Vordia\Http\Channels\Drivers\SmsDriverInterface;
use RuntimeException;

class Melipayamak implements SmsDriverInterface
{
    private string $username;
    private string $password;
    private string $pattern;

    public function __construct(array $config)
    {
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->pattern = $config['pattern'] ?? '';

        if (empty($this->username) || empty($this->password) || empty($this->pattern)) {
            throw new InvalidArgumentException('Melipayamak configuration is invalid.');
        }
    }

    public function send(string $receptor, array $parameters): void
    {
        $code = $parameters['code'] ?? '';

        $response = Http::post('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber', [
            'username' => $this->username,
            'password' => $this->password,
            'to' => $receptor,
            'bodyId' => $this->pattern,
            'text' => $code
        ]);

        if (!$response->successful() || $response->json('RetStatus') != 1) {
            throw new RuntimeException('Melipayamak error: ' . $response->body());
        }
    }
}

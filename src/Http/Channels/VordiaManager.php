<?php

namespace Rayiumir\Vordia\Http\Channels;

use InvalidArgumentException;
use Rayiumir\Vordia\Http\Channels\Drivers\SmsDriverInterface;

class VordiaManager
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('vordia.sms', []);
    }

    public function driver(?string $driver = null): SmsDriverInterface
    {
        $driver = $driver ?: $this->config['default'];
        $driverConfig = $this->config['drivers'][$driver] ?? null;

        if (is_null($driverConfig)) {
            throw new InvalidArgumentException("Driver [{$driver}] is not supported.");
        }

        $driverClass = $driverConfig['class'];
        return new $driverClass($driverConfig['config']);
    }
}

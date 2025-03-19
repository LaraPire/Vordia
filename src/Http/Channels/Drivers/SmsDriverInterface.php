<?php

namespace Rayiumir\Vordia\Http\Channels\Drivers;

interface SmsDriverInterface
{
    public function send(string $receptor, array $parameters): void;
}

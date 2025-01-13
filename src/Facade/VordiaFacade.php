<?php

namespace Rayiumir\Vordia\Facade;

use Illuminate\Support\Facades\Facade;

class VordiaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Vordia';
    }
}

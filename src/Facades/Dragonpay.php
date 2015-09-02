<?php

namespace Coreproc\Dragonpay\Facades;

use Illuminate\Support\Facades\Facade;

class Dragonpay extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'dragonpay';
    }

}
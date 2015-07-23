<?php

namespace Coreproc\Dragonpay\Facades;

use Illuminate\Support\Facades\Facade;

class DragonpayTransaction extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'transaction';
    }

}
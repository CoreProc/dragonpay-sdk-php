<?php

namespace Coreproc\Dragonpay\Facades;

use Illuminate\Support\Facades\Facade;

class DragonpayCheckout extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'checkout';
    }

}
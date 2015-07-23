<?php

namespace Coreproc\Dragonpay\Providers;

use Coreproc\Dragonpay\Checkout;
use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Transaction;
use Illuminate\Support\ServiceProvider;

class DragonpayServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $client = new DragonpayClient([
            'merchantId'       => config('dragonpay.merchant_id'),
            'merchantPassword' => config('dragonpay.merchant_password'),
            'logging'          => config('dragonpay.logging'),
            'logDirectory'     => config('dragonpay.log_directory'),
            'testing'          => config('dragonpay.testing'),
        ]);

        $this->app->bind('checkout', function () use ($client) {
            return new Checkout($client);
        });

        $this->app->bind('transaction', function () use ($client) {
            return new Transaction($client);
        });
    }

}
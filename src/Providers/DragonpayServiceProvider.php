<?php

namespace Coreproc\Dragonpay\Providers;

use Coreproc\Dragonpay\Checkout;
use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Transaction;
use Illuminate\Support\ServiceProvider;

class DragonpayServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '\Config\dragonpay.php' => config_path('dragonpay.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(config_path('dragonpay.php'))) {
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

}
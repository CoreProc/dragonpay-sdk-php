<?php
namespace Coreproc\Dragonpay\Providers;

use Coreproc\Dragonpay\DragonpayApi;
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
            dirname(__DIR__) . '..\..\config\dragonpay.php' => config_path('dragonpay.php'),
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
            $this->app->bind('dragonpay', function () {
                return new DragonpayApi([
                    'merchantId'       => config('dragonpay.merchantId'),
                    'merchantPassword' => config('dragonpay.merchantPassword'),
                    'logging'          => config('dragonpay.logging'),
                    'logDirectory'     => config('dragonpay.logDirectory'),
                    'testing'          => config('dragonpay.testing'),
                ]);
            });
        }
    }

}
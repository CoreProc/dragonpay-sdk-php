<?php

namespace Coreproc\Dragonpay\Classes\MerchantService;

class MerchantServiceFactory
{

    /**
     * @param $webService
     * @return RestMerchantService|SoapMerchantService
     */
    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SoapMerchantService();
                break;
            case 'REST':
            default:
                return new RestMerchantService();
                break;
        }
    }

}
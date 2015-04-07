<?php

namespace Coreproc\Dragonpay\MerchantService;

use Coreproc\Dragonpay\Exceptions\InvalidWebServiceException;

class MerchantServiceFactory
{

    /**
     * @param $webService
     * @return RestMerchantService|SoapMerchantService
     * @throws InvalidWebServiceException
     */
    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SoapMerchantService();
                break;
            case 'REST':
                return new RestMerchantService();
                break;
            default:
                throw new InvalidWebServiceException('Please set a valid web service.');
                break;
        }
    }

}
<?php

namespace Coreproc\Dragonpay\UrlGenerator;

use Coreproc\Dragonpay\Exceptions\InvalidWebServiceException;

class UrlGeneratorFactory
{

    /**
     * @param $webService
     * @return RestUrlGenerator|SoapUrlGenerator
     * @throws InvalidWebServiceException
     */
    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SoapUrlGenerator();
                break;
            case 'REST':
                return new RestUrlGenerator();
                break;
            default:
                throw new InvalidWebServiceException('Please set a valid web service.');
                break;
        }
    }

}
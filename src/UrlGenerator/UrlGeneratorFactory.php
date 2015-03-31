<?php

namespace Coreproc\Dragonpay\UrlGenerator;

class UrlGeneratorFactory
{

    /**
     * @param $webService
     * @return RestUrlGenerator|SoapUrlGenerator
     */
    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SoapUrlGenerator();
                break;
            case 'REST':
            default:
                return new RestUrlGenerator();
                break;
        }
    }

}
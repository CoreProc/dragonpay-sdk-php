<?php

namespace Coreproc\Dragonpay\Classes\URLGenerator;

class URLGeneratorFactory
{

    /**
     * @param $webService
     * @return RESTURLGenerator|SOAPURLGenerator
     */
    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SOAPURLGenerator();
                break;
            case 'REST':
            default:
                return new RESTURLGenerator();
                break;
        }
    }

}
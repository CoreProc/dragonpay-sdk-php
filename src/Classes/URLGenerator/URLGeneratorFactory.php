<?php

namespace Coreproc\Dragonpay\Classes\URLGenerator;

class URLGeneratorFactory
{

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
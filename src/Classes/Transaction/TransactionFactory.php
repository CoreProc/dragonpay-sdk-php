<?php

namespace Coreproc\Dragonpay\Classes\Transaction;

class TransactionFactory
{

    public static function create($webService)
    {
        switch ($webService) {
            case 'SOAP':
                return new SOAPTransaction();
                break;
            case 'REST':
            default:
                return new RESTTransaction();
                break;
        }
    }

}
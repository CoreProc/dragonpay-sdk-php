<?php

namespace Coreproc\Dragonpay\Classes\MerchantService;

use SoapClient;

class SoapMerchantService implements MerchantServiceInterface
{

    /**
     * Dragonpay Web Service URL
     *
     * @var string
     * @TODO Put this in a config file
     */
    private $webServiceURL = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    public function __construct()
    {
        $this->SOAPClient = new SoapClient($this->webServiceURL);
    }

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @return mixed
     */
    public function inquire(array $credentials)
    {
        $params = [
            'merchantId' => $credentials['merchantId'],
            'password'   => $credentials['merchantPassword'],
            'txnId'      => $credentials['transactionId'],
        ];

        $response = $this->SOAPClient->__soapCall('GetTxnStatus', [$params]);

        return $response->GetTxnStatusResult;
    }

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @return mixed
     */
    public function cancel(array $credentials)
    {
        $params = [
            'merchantId' => $credentials['merchantId'],
            'password'   => $credentials['merchantPassword'],
            'txnId'      => $credentials['transactionId'],
        ];

        $response = $this->SOAPClient->__soapCall('CancelTransaction', [$params]);

        return $response->CancelTransactionResult;
    }

}
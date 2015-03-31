<?php

namespace Coreproc\Dragonpay\MerchantService;

use SoapClient;

class SoapMerchantService implements MerchantServiceInterface, BillingServiceInterface
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
     * @param string $transactionId
     * @return mixed
     */
    public function inquire(array $credentials, $transactionId)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->SOAPClient->__soapCall('GetTxnStatus', [$params]);

        return $response->GetTxnStatusResult;
    }

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param string $transactionId
     * @return mixed
     */
    public function cancel(array $credentials, $transactionId)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->SOAPClient->__soapCall('CancelTransaction', [$params]);

        return $response->CancelTransactionResult;
    }

    /**
     * Send billing information of customer's billing address to the Dragonpay
     * Payment Switch API for additional fraud checking.
     *
     * @param string $merchantId
     * @param array $params
     * @return mixed
     */
    public function sendBillingInformation($merchantId, array $params)
    {
        $params['merchantId'] = $merchantId;
        $params['merchantTxnId'] = $params['transactionId'];

        unset($params['transactionId']);

        $response = $this->SOAPClient->__soapCall('SendBillingInfo', [$params]);

        return $response->SendBillingInfoResult;
    }

    /**
     * Set the parameters for transaction inquiry and cancellation.
     *
     * @param array $credentials
     * @return array
     */
    private function setParams(array $credentials, $transactionId)
    {
        $params = [
            'merchantId' => $credentials['merchantId'],
            'password'   => $credentials['merchantPassword'],
            'txnId'      => $transactionId,
        ];

        return $params;
    }

}
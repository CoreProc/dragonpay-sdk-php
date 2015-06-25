<?php

namespace Coreproc\Dragonpay\MerchantService;

use SoapClient;

class SoapMerchantService implements MerchantServiceInterface, BillingServiceInterface
{

    /**
     * Dragonpay Web Service URL
     *
     * @var string
     * @TODO Refactor (Duplicated definition of URLs)
     */
    private $webServiceURL = 'https://gw.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    /**
     * Dragonpay Test Web Service URL
     *
     * @var string
     * @TODO Refactor (Duplicated definition of URLs)
     */
    private $testWebServiceURL = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param string $transactionId
     * @return mixed
     */
    public function inquire(array $credentials, $transactionId, $testing)
    {
        $params = $this->setParams($credentials, $transactionId);

        $soapClient = $this->createSoapClient($testing);

        $response = $soapClient->__soapCall('GetTxnStatus', [$params]);

        return $response->GetTxnStatusResult;
    }

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param string $transactionId
     * @param $testing
     * @return mixed
     */
    public function cancel(array $credentials, $transactionId, $testing)
    {
        $params = $this->setParams($credentials, $transactionId);

        $soapClient = $this->createSoapClient($testing);

        $response = $soapClient->__soapCall('CancelTransaction', [$params]);

        return $response->CancelTransactionResult;
    }

    /**
     * Send billing information of customer's billing address to the Dragonpay
     * Payment Switch API for additional fraud checking.
     *
     * @param string $merchantId
     * @param array $params
     * @param $testing
     * @return mixed
     */
    public function sendBillingInformation($merchantId, array $params, $testing)
    {
        $params['merchantId'] = $merchantId;
        $params['merchantTxnId'] = $params['transactionId'];

        unset($params['transactionId']);

        $soapClient = $this->createSoapClient($testing);

        $response = $soapClient->__soapCall('SendBillingInfo', [$params]);

        return $response->SendBillingInfoResult;
    }

    /**
     * Set the parameters for transaction inquiry and cancellation.
     *
     * @param array $credentials
     * @param $transactionId
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

    /**
     * @param $testing
     * @return SoapClient
     */
    public function createSoapClient($testing)
    {
        $url = $testing ? $this->testWebServiceURL : $this->webServiceURL;

        $soapClient = new SoapClient($url);

        return $soapClient;
    }

}
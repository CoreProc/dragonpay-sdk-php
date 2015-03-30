<?php

namespace Coreproc\Dragonpay\Classes\UrlGenerator;

use SoapClient;

class SoapUrlGenerator implements UrlGeneratorInterface
{

    /**
     * Dragonpay Payment Switch Base URL
     *
     * @var string
     * @TODO Put this in a config file
     */
    private $basePaymentURL = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * Dragonpay Web Service URL
     *
     * @var string
     * @TODO Put this in a config file
     */
    private $webServiceURL = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param $params
     * @return string
     */
    public function generate($params)
    {
        $soapClient = new SoapClient($this->webServiceURL);

        $params = $this->setParamKeys($params);

        $response = $soapClient->__soapCall('GetTxnToken', [$params]);

        $tokenId = $response->GetTxnTokenResult;

        $url = $this->basePaymentURL . "?tokenid={$tokenId}";

        return $url;
    }

    /**
     * Set the parameter keys required by the Dragonpay Payment Switch.
     *
     * @param array $params
     * @return array
     */
    private function setParamKeys(array $params)
    {
        $params['merchantTxnId'] = $params['transactionId'];
        $params['ccy'] = $params['currency'];

        unset($params['transactionId']);
        unset($params['currency']);

        return $params;
    }

}
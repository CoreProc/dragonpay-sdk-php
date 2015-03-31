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
    private $basePaymentUrl = 'http://test.dragonpay.ph/Pay.aspx';

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
     * @param array $params
     * @return string
     */
    public function generate(array $params)
    {
        $soapClient = new SoapClient($this->webServiceURL);

        $params = $this->setParams($params);

        $response = $soapClient->__soapCall('GetTxnToken', [$params]);

        $tokenId = $response->GetTxnTokenResult;

        $url = $this->basePaymentUrl . "?tokenid={$tokenId}";

        return $url;
    }

    /**
     * Set the parameters required by the Dragonpay Payment Switch.
     *
     * @param array $params
     * @return array
     */
    private function setParams(array $params)
    {
        $params['merchantTxnId'] = $params['transactionId'];
        $params['ccy'] = $params['currency'];

        unset($params['transactionId']);
        unset($params['currency']);

        return $params;
    }

}
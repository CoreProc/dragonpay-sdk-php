<?php

namespace Coreproc\Dragonpay\Classes\URLGenerator;

use SoapClient;

class SOAPURLGenerator implements URLGeneratorInterface
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

    public function generate($params)
    {
        $soapClient = new SoapClient($this->webServiceURL);

        $params = $this->setParamKeys($params);

        $response = $soapClient->__soapCall('GetTxnToken', [$params]);

        $tokenId = $response->GetTxnTokenResult;

        $url = $this->basePaymentURL . "?tokenid={$tokenId}";

        return $url;
    }

    private function setParamKeys(array $params)
    {
        $params['merchantTxnId'] = $params['transactionId'];
        $params['ccy'] = $params['currency'];

        unset($params['transactionId']);
        unset($params['currency']);

        return $params;
    }

}
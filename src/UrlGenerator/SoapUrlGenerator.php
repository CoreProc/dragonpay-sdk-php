<?php

namespace Coreproc\Dragonpay\UrlGenerator;

use SoapClient;

class SoapUrlGenerator extends UrlGenerator implements UrlGeneratorInterface
{

    /**
     * @var array Validation rules
     */
    protected $rules = [
        'merchantId',
        'password',
        'transactionId',
        'amount',
        'currency',
        'description',
    ];

    /**
     * Dragonpay Web Service URL
     *
     * @var string
     */
    private $webServiceURL = 'https://secure.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    /**
     * Dragonpay Test Web Service URL
     *
     * @var string
     */
    private $testWebServiceURL = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?WSDL';

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param array $params
     * @param $testing
     * @return string
     * @throws \Coreproc\Dragonpay\Exceptions\ValidationException
     */
    public function generate(array $params, $testing)
    {
        $this->validate($params);

        $serviceUrl = $testing ? $this->testWebServiceURL : $this->webServiceURL;

        $soapClient = new SoapClient($serviceUrl);

        $params = $this->setParams($params);

        $response = $soapClient->__soapCall('GetTxnToken', [$params]);

        $tokenId = $response->GetTxnTokenResult;

        $baseUrl = $testing ? $this->testPaymentUrl : $this->basePaymentUrl;

        $url = $baseUrl . "?tokenid={$tokenId}";

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
<?php

namespace Coreproc\Dragonpay\UrlGenerator;

use SoapClient;
use Valitron\Validator;
use Coreproc\Dragonpay\Exceptions\ValidationException;

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
        $this->validate($params);

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

    /**
     * Validate required parameters for URL Generation.
     *
     * @param array $params
     * @throws ValidationException
     */
    private function validate(array $params)
    {
        $validator = new Validator($params);

        $validator->rule('required', [
            'merchantId',
            'password',
            'transactionId',
            'amount',
            'currency',
            'description',
        ]);

        if ( ! $validator->validate()) {
            $errors = '';

            foreach ($validator->errors() as $key => $value) {
                $errors .= $key . ' is required. ';
            }

            throw new ValidationException($errors);
        }
    }

}
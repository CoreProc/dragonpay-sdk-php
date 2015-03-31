<?php

namespace Coreproc\Dragonpay\UrlGenerator;

use Valitron\Validator;
use Coreproc\Dragonpay\Exceptions\ValidationException;

class RestUrlGenerator implements UrlGeneratorInterface
{

    /**
     * Dragonpay Payment Switch Base URL
     *
     * @var string
     * @TODO Put this in a config file
     */
    private $basePaymentUrl = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param array $params
     * @return string
     */
    public function generate(array $params)
    {
        $this->validate($params);

        $queryString = sprintf(
            'merchantid=%s&txnid=%s&amount=%s&ccy=%s&description=%s&email=%s',
            urlencode($params['merchantId']),
            urlencode($params['transactionId']),
            urlencode($params['amount']),
            urlencode($params['currency']),
            urlencode($params['description']),
            urlencode($params['email'])
        );

        // Optional
        // param1: value to be posted back to merchant url when completed
        if (isset($params['param1'])) {
            $queryString .= '%26param1=' . urlencode($params['param1']);
        }

        // Optional
        // param2: value to be posted back to merchant url when completed
        if (isset($params['param2'])) {
            $queryString .= '%26param2=' . urlencode($params['param2']);
        }

        $digest = $this->generateDigest($params);

        // Append generated digest
        $queryString .= '&digest=' . urlencode($digest);

        $url = "$this->basePaymentUrl?$queryString";

        return $url;
    }

    /**
     * Generate a digest to be appended to the generated URL's params.
     *
     * @param array $params
     * @return string
     */
    private function generateDigest(array $params)
    {
        $string = sprintf(
            '%s:%s:%s:%s:%s:%s:%s',
            $params['merchantId'],
            $params['transactionId'],
            $params['amount'],
            $params['currency'],
            $params['description'],
            $params['email'],
            $params['password']
        );

        return sha1($string);
    }

    /**
     * @param array $params
     * @throws ValidationException
     */
    private function validate(array $params)
    {
        $validator = new Validator($params);

        $validator->rule('required', [
            'merchantId',
            'transactionId',
            'amount',
            'currency',
            'description',
            'email',
            'password'
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
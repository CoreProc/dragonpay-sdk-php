<?php

namespace Coreproc\Dragonpay\Classes\URLGenerator;

class RESTURLGenerator implements URLGeneratorInterface
{

    /**
     * Dragonpay Payment Switch Base URL
     *
     * @var string
     * @TODO Put this in a config file
     */
    private $basePaymentURL = 'http://test.dragonpay.ph/Pay.aspx';

    public function generate($params)
    {
        $data['digest'] = $this->generateDigest($params);

        $queryString = 'merchantid=' . urlencode($params['merchantId'])
            . '&txnid=' . urlencode($params['transactionId'])
            . '&amount=' . urlencode($params['amount'])
            . '&ccy=' . urlencode($params['currency'])
            . '&description=' . urlencode($params['description'])
            . '&email=' . urlencode($params['email']);

        // Optional
        // param1: value to be posted back to merchant url when completed
        if (isset($params['param1'])) {
            $queryString .= '%26param1=' . urlencode($data['param1']);
        }

        // Optional
        // param2: value to be posted back to merchant url when completed
        if (isset($params['param2'])) {
            $queryString .= '%26param2=' . urlencode($data['param2']);
        }

        // Append generated digest
        $queryString .= '&digest=' . urlencode($data['digest']);

        $url = "$this->basePaymentURL?$queryString";

        return $url;
    }

    /**
     * Generate a digest to be appended to the generated URL's params.
     *
     * @param array $data
     * @return string
     */
    private function generateDigest(array $params)
    {
        $message = "{$params['merchantId']}:{$params['transactionId']}:{$params['amount']}"
            . ":{$params['currency']}:{$params['description']}:{$params['email']}"
            . ":{$params['password']}";

        return sha1($message);
    }

}
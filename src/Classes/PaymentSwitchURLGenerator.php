<?php namespace Coreproc\Dragonpay\Classes;

class PaymentSwitchURLGenerator
{

    // Payment URLs
    private $baseUrl = 'http://gw.dragonpay.ph/Pay.aspx';
    private $testUrl = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * Generate the URL for redirecting the merchant to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function generate(array $data)
    {
        $digest = $this->generateDigest($data);

        $params = 'merchantid=' . urlencode($data['merchantId'])
            . '&txnid=' . urlencode($data['transactionId'])
            . '&amount=' . urlencode($data['amount'])
            . '&ccy=' . urlencode($data['currency'])
            . '&description=' . urlencode($data['description'])
            . '&email=' . urlencode($data['email']);

        // OPTIONAL: values to be posted back to merchant url when completed
        if (isset($data['param1'])) {
            $params .= '%26param1=' . urlencode($data['param1']);
        }

        if (isset($data['param2'])) {
            $params .= '%26param2=' . urlencode($data['param2']);
        }

        $params .= '&digest=' . urlencode($digest);

        $url = "$this->testUrl?$params";

        return $url;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateDigest(array $data)
    {
        $message = "{$data['merchantId']}:{$data['transactionId']}:{$data['amount']}"
            . ":{$data['currency']}:{$data['description']}:{$data['email']}"
            . ":{$data['secretKey']}";

        return sha1($message);
    }

}
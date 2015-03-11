<?php namespace Coreproc\Dragonpay\Classes;

class URLGenerator
{

    // Payment URLs
    private $basePaymentUrl = 'http://gw.dragonpay.ph/Pay.aspx';
    private $testPaymentUrl = 'http://test.dragonpay.ph/Pay.aspx';

    // Support URLs
    private $baseSupportUrl = 'http://gw.dragonpay.ph/MerchantRequest.aspx';
    private $testSupportUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

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
            . '&ccy=' . urlencode($data['ccy'])
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

        $url = "$this->basePaymentUrl?$params";

        return $url;
    }

    /**
     * Generate the URL for inquiring a transaction's status.
     *
     * @param $merchantId
     * @param $merchantPassword
     * @param $transactionId
     * @param $operation
     * @return string
     */
    public function generateTransactionQueryUrl($merchantId, $merchantPassword, $transactionId, $operation)
    {
        $params = "op={$operation}&merchantid={$merchantId}&merchantpwd={$merchantPassword}"
            . "&txnid={$transactionId}";

        $url = "$this->baseSupportUrl?$params";

        return $url;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateDigest(array $data)
    {
        $message = "{$data['merchantId']}:{$data['transactionId']}:{$data['amount']}"
            . ":{$data['ccy']}:{$data['description']}:{$data['email']}"
            . ":{$data['secretKey']}";

        return sha1($message);
    }

}
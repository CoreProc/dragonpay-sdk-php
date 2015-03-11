<?php namespace Coreproc\Dragonpay\Classes;

class URLGenerator
{

    private $baseUrl = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * Generate the URL for redirecting the merchant to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function generate(array $data)
    {
        $digest = $this->generateDigest($data);

        $params = 'merchantid=' . urlencode($data['merchantid'])
            . '&txnid=' . urlencode($data['txnid'])
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

        $url = "$this->baseUrl?$params";

        return $url;
    }

    /**
     * Generate the URL for inquiring a transaction's status.
     *
     * @param $merchantId
     * @param $merchantPwd
     * @param $txnId
     * @param $operation
     * @return string
     */
    public function generateTxnQueryUrl($merchantId, $merchantPwd, $txnId, $operation)
    {
        $params = "op={$operation}&merchantid={$merchantId}&merchantpwd={$merchantPwd}"
            . "&txnid={$txnId}";

        $url = "$this->baseUrl?$params";

        return $url;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateDigest(array $data)
    {
        $message = "{$data['merchantid']}:{$data['txnid']}:{$data['amount']}"
            . ":{$data['ccy']}:{$data['description']}:{$data['email']}"
            . ":{$data['secretkey']}";

        return sha1($message);
    }

}
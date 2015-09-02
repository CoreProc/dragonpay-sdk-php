<?php

namespace Coreproc\Dragonpay\Transaction;

use GuzzleHttp\Client;

class RestTransaction implements TransactionInterface
{

    public $baseUri;

    /**
     * @param $client
     * @param bool $testing
     */
    public function __construct($client = null, $testing = false)
    {
        $this->baseUri = $testing ? TESTING_BASE_URI : PRODUCTION_BASE_URI;
        $this->client = $client ?: new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isValid(array $params)
    {
        return $this->buildDigest($params) === $params['digest'];
    }

    /**
     * @param array $params
     * @return string Status response
     */
    public function inquire(array $params)
    {
        return $this->merchantRequest('GETSTATUS', $params);
    }

    /**
     * @param array $params
     * @return string Status response
     */
    public function cancel(array $params)
    {
        return $this->merchantRequest('VOID', $params);
    }

    /**
     * @param $operation
     * @param array $params
     * @return string Status response
     */
    private function merchantRequest($operation, array $params)
    {
        $response = $this->client->get('MerchantRequest.aspx', [
            'query' => [
                'op'          => $operation,
                'merchantid'  => $params['merchantId'],
                'merchantpwd' => $params['merchantPassword'],
                'txnid'       => $params['transactionId'],
            ]
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param $params
     * @return string
     */
    private function buildDigest($params)
    {
        $message = sprintf(
            '%s:%s:%s:%s:%s',
            $params['transactionId'],
            $params['referenceNumber'],
            $params['status'],
            $params['message'],
            $params['merchantPassword']
        );

        return sha1($message);
    }

}
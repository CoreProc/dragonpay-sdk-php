<?php

namespace Coreproc\Dragonpay\MerchantService;

use GuzzleHttp\Client;

class RestMerchantService implements MerchantServiceInterface
{

    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var string URL for querying a transaction
     * @TODO Refactor (Duplicated definition of URLs)
     */
    private $merchantRequestUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    /**
     * @var string Test URL for querying a transaction
     * @TODO Refactor (Duplicated definition of URLs)
     */
    private $testMerchantRequestUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param string $transactionId
     * @param $testing
     * @return \GuzzleHttp\Stream\StreamInterface|null
     */
    public function inquire(array $credentials, $transactionId, $testing)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->doRequest('GETSTATUS', $params, $testing);

        return $response->getBody();
    }

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param string $transactionId
     * @param $testing
     * @return string
     */
    public function cancel(array $credentials, $transactionId, $testing)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->doRequest('VOID', $params, $testing);

        return (string) $response->getBody();
    }

    /**
     * Set the parameters required by the Dragonpay Payment Switch.
     *
     * @param array $credentials
     * @param string $transactionId
     * @return array
     */
    private function setParams(array $credentials, $transactionId)
    {
        $params = $credentials;
        $params['transactionId'] = $transactionId;

        return $params;
    }

    /**
     * Perform a GET request to the Dragonpay Merchant Request URL.
     *
     * @param $operation
     * @param array $params
     * @param $testing
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    private function doRequest($operation, array $params, $testing)
    {
        $url = $testing ? $this->testMerchantRequestUrl : $this->merchantRequestUrl;

        return $this->guzzleClient->get($url, [
            'query' => [
                'op'          => $operation,
                'merchantid'  => $params['merchantId'],
                'merchantpwd' => $params['merchantPassword'],
                'txnid'       => $params['transactionId']
            ],
        ]);
    }

}
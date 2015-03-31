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
     * @TODO put this in a config file
     */
    private $merchantRequestUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param string $transactionId
     * @return \GuzzleHttp\Stream\StreamInterface|null
     */
    public function inquire(array $credentials, $transactionId)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->doRequest('GETSTATUS', $params);

        return $response->getBody();
    }

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param string $transactionId
     * @return string
     */
    public function cancel(array $credentials, $transactionId)
    {
        $params = $this->setParams($credentials, $transactionId);

        $response = $this->doRequest('VOID', $params);

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
     */
    private function doRequest($operation, array $params)
    {
        return $this->guzzleClient->get($this->merchantRequestUrl, [
            'query' => [
                'op'          => $operation,
                'merchantid'  => $params['merchantId'],
                'merchantpwd' => $params['merchantPassword'],
                'txnid'       => $params['transactionId']
            ],
        ]);
    }

}
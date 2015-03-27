<?php

namespace Coreproc\Dragonpay\Classes\Transaction;

use GuzzleHttp\Client;

class RESTTransaction implements TransactionInterface
{

    private $guzzleClient;

    /**
     * @var string URL for querying a transaction
     * @TODO put this in a config file
     */
    private $merchantRequestURL = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    public function inquire(array $credentials)
    {
        $response = $this->merchantRequest('GETSTATUS', $credentials);

        return $response->getBody();
    }

    public function cancel(array $credentials)
    {
        $response = $this->merchantRequest('VOID', $credentials);

        return (string) $response->getBody();
    }

    private function merchantRequest($operation, array $credentials)
    {
        return $this->guzzleClient->get($this->merchantRequestURL, [
            'query' => [
                'op'          => $operation,
                'merchantid'  => $credentials['merchantId'],
                'merchantpwd' => $credentials['merchantPassword'],
                'txnid'       => $credentials['transactionId']
            ],
        ]);
    }

}
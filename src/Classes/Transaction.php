<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\MerchantService\MerchantServiceFactory;

class Transaction
{

    /**
     * @var DragonpayClient
     */
    private $client;

    /**
     * @var MerchantService\RestMerchantService|MerchantService\SoapMerchantService
     */
    private $merchantService;

    private $credentials;

    public function __construct(DragonpayClient $client, $webService = 'REST')
    {
        $this->client = $client;
        $this->setMerchantCredentials();
        $this->merchantService = MerchantServiceFactory::create($webService);
    }

    /**
     * Inquire for a transaction's status.
     *
     * @param $transactionId
     * @return string
     */
    public function inquire($transactionId)
    {
        $code = $this->merchantService->inquire($this->credentials, $transactionId);

        return $this->parseTransactionStatusCode($code);
    }

    /**
     * Cancel a transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function cancel($transactionId)
    {
        $code = $this->merchantService->cancel($this->credentials, $transactionId);

        return $this->parseTransactionCancellationStatusCode($code);
    }

    /**
     * Parse the status code response of the transaction inquiry.
     *
     * @param $code
     * @return string
     * @TODO Make a parser class?
     */
    private function parseTransactionStatusCode($code)
    {
        switch ($code) {
            case 'S':
                $status = 'Success';
                break;
            case 'F':
                $status = 'Failure';
                break;
            case 'P':
                $status = 'Pending';
                break;
            case 'U':
                $status = 'Unknown';
                break;
            case 'R':
                $status = 'Refund';
                break;
            case 'K':
                $status = 'Chargeback';
                break;
            case 'V':
                $status = 'Void';
                break;
            case 'A':
                $status = 'Authorized';
                break;
            default:
                $status = 'Error';
                break;
        }

        return $status;
    }

    /**
     * Parse the status code response of the transaction cancellation.
     *
     * @param $code
     * @return string
     * @TODO Make a parser class?
     */
    private function parseTransactionCancellationStatusCode($code)
    {
        if ($code == 0) {
            return 'Success';
        }

        return 'Failed';

    }

    /**
     * Set the merchant credentials.
     */
    private function setMerchantCredentials()
    {
        $this->credentials['merchantId'] = $this->client->getMerchantId();
        $this->credentials['merchantPassword'] = $this->client->getMerchantPassword();
    }

}
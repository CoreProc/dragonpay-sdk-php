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
     * @TODO Clean up logging
     */
    public function inquire($transactionId)
    {
        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-inquiry] Inquiring status of Transaction ID {$transactionId}";
            $this->client->getLogger()->info($logMessage);
        }

        $code = $this->merchantService->inquire($this->credentials, $transactionId);

        $status = $this->parseTransactionStatusCode($code);

        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-inquiry] Status inquiry of Transaction ID {$transactionId} returned the status of \"{$status}\".";
            $this->client->getLogger()->info($logMessage);
        }

        return $status;
    }

    /**
     * Cancel a transaction.
     *
     * @param $transactionId
     * @return string
     * @TODO Clean up logging
     */
    public function cancel($transactionId)
    {
        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-cancellation] Transaction ID {$transactionId} is being cancelled.";
            $this->client->getLogger()->info($logMessage);
        }

        $code = $this->merchantService->cancel($this->credentials, $transactionId);

        $status = $this->parseStatusCode($code);

        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-cancellation] Cancellation of Transaction ID {$transactionId} returned the status of \"{$status}\".";
            $this->client->getLogger()->info($logMessage);
        }

        return $status;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function sendBillingInformation(array $params)
    {
        $merchantId = $this->client->getMerchantId();

        $code = $this->merchantService->sendBillingInformation($merchantId, $params);

        $status = $this->parseStatusCode($code);

        return $status;
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
     * Return the string representation of a transaction cancellation/sending of
     * billing information's status code.
     *
     * @param $code
     * @return string
     * @TODO Make a parser class?
     */
    private function parseStatusCode($code)
    {
        return $code == 0 ? 'Success' : 'Failed';
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
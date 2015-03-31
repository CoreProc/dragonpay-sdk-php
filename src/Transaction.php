<?php

namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\MerchantService\MerchantServiceFactory;

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

    /**
     * @var array Merchant credentials
     */
    private $credentials;

    /**
     * @param DragonpayClient $client
     * @param string $webService
     */
    public function __construct(DragonpayClient $client, $webService = 'REST')
    {
        $this->client = $client;
        $this->setMerchantCredentials();
        $this->merchantService = MerchantServiceFactory::create($webService);
    }

    /**
     * Inquire for a transaction's status.
     *
     * @param string $transactionId
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
     * @param string $transactionId
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
     * Send billing information of customer's billing address to the Dragonpay
     * Payment Switch API for additional fraud checking.
     *
     * @param array $params
     * @return string
     */
    public function sendBillingInformation(array $params)
    {
        $merchantId = $this->client->getMerchantId();

        $code = $this->merchantService->sendBillingInformation($merchantId, $params);

        $status = $this->parseStatusCode($code);

        return $status;
    }

    /**
     * Checks if a transaction is successful.
     *
     * @param array $params
     * @return bool
     */
    public function isSuccessful(array $params)
    {
        $responseDigest = $this->generateResponseDigest($params);

        $status = $this->parseTransactionStatusCode($params['status']);

        if ($responseDigest == $params['digest'] && $status == 'Success') {
            return true;
        }

        return false;
    }

    /**
     * Generates a digest to be compared to the Dragonpay Payment Switch
     * response digest parameter.
     *
     * @param $params
     * @return string
     */
    private function generateResponseDigest($params)
    {
        $string = sprintf(
            '%s:%s:%s,%s,%s',
            $params['transactionId'],
            $params['referenceNumber'],
            $params['status'],
            $params['message'],
            $this->client->getMerchantPassword()
        );

        return sha1($string);
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
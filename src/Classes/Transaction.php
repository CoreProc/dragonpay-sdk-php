<?php namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use GuzzleHttp\Client;

class Transaction
{

    /**
     * @var string Base URL for querying a transaction
     */
    private $baseUrl = 'https://gw.dragonpay.ph/MerchantRequest.aspx';

    /**
     * @var string Test URL for querying a transaction
     */
    private $testUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    /**
     * @var DragonpayClient
     */
    private $client;

    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var \Katzgrau\KLogger\Logger
     */
    private $log;

    /**
     * @var string Status of a transaction
     */
    private $status;

    /**
     * @var string Error of a transaction
     */
    private $error;

    /**
     * @param DragonpayClient $client
     */
    public function __construct(DragonpayClient $client)
    {
        $this->client = $client;
        $this->guzzleClient = new Client();

        if ($this->client->isLoggingEnabled()) {
            $this->log = $this->client->getLogger();
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * Inquire for the status of transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function statusInquiry($transactionId)
    {
        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-status-inquiry] "
                . "Inquiring the status of Transaction ID {$transactionId}";
            $this->log->info($logMessage);
        }

        $response = $this->guzzleClient->get($this->testUrl, [
            'query' => [
                'op'          => 'GETSTATUS',
                'merchantid'  => $this->client->getMerchantId(),
                'merchantpwd' => $this->client->getMerchantPassword(),
                'txnid'       => $transactionId
            ],
        ]);

        $responseBody = $response->getBody();

        $status = $this->parseStatusCode($responseBody);

        if ($this->client->isLoggingEnabled()) {
            if ($status == 'Error') {
                $logMessage = "[dragonpay-sdk][transaction-status-inquiry] "
                    . "Status inquiry error - Transaction ID "
                    . "{$transactionId}: {$responseBody}";
                $this->log->error($logMessage);
            } else {
                $logMessage = "[dragonpay-sdk][transaction-status-inquiry] "
                    . "Transaction ID {$transactionId} received the status of: {$status}";
                $this->log->info($logMessage);
            }
        }

        return $status;
    }

    /**
     * Cancel a transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function cancel($transactionId)
    {
        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][transaction-cancellation] Cancelling"
                . " Transaction ID {$transactionId}";
            $this->log->info($logMessage);
        }

        $response = $this->guzzleClient->get($this->testUrl, [
            'query' => [
                'op'          => 'VOID123',
                'merchantid'  => $this->client->getMerchantId(),
                'merchantpwd' => $this->client->getMerchantPassword(),
                'txnid'       => $transactionId
            ],
        ]);

        $responseBody = $response->getBody();

        $status = $this->parseCancellationStatusCode($responseBody);

        if ($this->client->isLoggingEnabled()) {
            if ($status == 'Error') {
                $logMessage = "[dragonpay-sdk][transaction-cancellation] "
                    . "Cancellation of Transaction ID "
                    . "{$transactionId} error: {$responseBody}";
                $this->log->error($logMessage);
            } else {
                $logMessage = "[dragonpay-sdk][transaction-cancellation] "
                    . "Transaction ID {$transactionId} cancellation status: {$status}";
                $this->log->info($logMessage);
            }
        }

        return $status;
    }

    /**
     * Checks if a transaction is successful.
     *
     * @param array $data
     * @return bool
     */
    public function isSuccessful(array $data)
    {
        $status = $this->parseStatusCode($data['status']);

        $this->status = $status;

        $digest = $this->generateDigest($data);

        if ($digest == $data['digest'] && $status == 'Success') {
            if ($this->client->isLoggingEnabled()) {
                $logMessage = "[dragonpay-sdk][transaction-response] Transaction ID "
                    . "{$data['transactionId']} with refno {$data['refno']} returned"
                    . "a status of success.";
                $this->log->info($logMessage);
            }

            return true;
        }

        return false;
    }


    /**
     * Checks if a transaction fails.
     *
     * @param array $data
     * @return bool
     */
    public function fails(array $data)
    {
        $status = $this->parseStatusCode($data['status']);

        if ($status == 'Failure') {
            if ($this->client->isLoggingEnabled()) {
                $logMessage = "[dragonpay-sdk][transaction-response] Transaction ID "
                    . "{$data['transactionId']} with refno {$data['refno']} returned"
                    . " a status of failure.";
                $this->log->error($logMessage);
            }

            $this->error = $this->parseErrorCode($data['message']);

            return true;
        }

        return false;
    }

    /**
     * Get the string representation of a transaction's status code.
     *
     * @param $code
     * @return string
     */
    private function parseStatusCode($code)
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
     * Get the string representation of a transaction's cancellation status code.
     *
     * @param $code
     * @return string
     */
    private function parseCancellationStatusCode($code)
    {
        switch ($code) {
            case '0':
                return 'Success';
                break;
            case '-1':
                return 'Failed';
                break;
            default:
                return 'Error';
                break;
        }
    }

    /**
     * Get the string representation of a transaction's error code.
     *
     * @param $code
     * @return string
     */
    private function parseErrorCode($code)
    {
        $error = '';

        switch ($code) {
            case 000:
                $error = 'Success';
                break;
            case 101:
                $error = 'Invalid payment gateway id';
                break;
            case 102:
                $error = 'Incorrect secret key';
                break;
            case 103:
                $error = 'Invalid reference number';
                break;
            case 104:
                $error = 'Unauthorized access';
                break;
            case 105:
                $error = 'Invalid token';
                break;
            case 106:
                $error = 'Currency not supported';
                break;
            case 107:
                $error = 'Transaction cancelled';
                break;
            case 108:
                $error = 'Insufficient funds';
                break;
            case 109:
                $error = 'Transaction limit exceeded';
                break;
            case 110:
                $error = 'Error in operation';
                break;
            case 111:
                $error = 'Invalid parameters';
                break;
            case 201:
                $error = 'Invalid Merchant Id';
                break;
            case 202:
                $error = 'Invalid Merchant Password';
                break;
        }

        return $error;
    }

    /**
     * @param $data
     * @return string
     */
    private function generateDigest($data)
    {
        $message = "{$data['transactionId']}:{$data['refno']}:{$data['status']}"
            . ":{$data['message']}:{$this->client->getSecretKey()}";

        return sha1($message);
    }

}
<?php namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use GuzzleHttp\Client;

class Transaction
{

    // Support URLs
    private $baseUrl = 'http://gw.dragonpay.ph/MerchantRequest.aspx';
    private $testUrl = 'http://test.dragonpay.ph/MerchantRequest.aspx';

    private $client;

    private $guzzleClient;

    public function __construct(DragonpayClient $client)
    {
        $this->client = $client;
        $this->guzzleClient = new Client();
    }

    /**
     * Inquire for the status of transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function statusInquiry($transactionId)
    {
        $response = $this->guzzleClient->get($this->testUrl, [
            'query' => [
                'op'          => 'GETSTATUS',
                'merchantid'  => $this->client->getMerchantId(),
                'merchantpwd' => $this->client->getMerchantPassword(),
                'txnid'       => $transactionId
            ],
        ]);

        $code = $response->getBody();

        return $this->parseStatusCode($code);
    }

    /**
     * Cancel a transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function cancel($transactionId)
    {
        $response = $this->guzzleClient->get($this->testUrl, [
            'query' => [
                'op'          => 'VOID',
                'merchantid'  => $this->client->getMerchantId(),
                'merchantpwd' => $this->client->getMerchantPassword(),
                'txnid'       => $transactionId
            ],
        ]);

        $code = $response->getBody();

        return $this->parseCancellationStatusCode($code);
    }

    /**
     * Check if a transaction is successful.
     *
     * @param $message
     * @param $digest
     * @param $statusCode
     * @return bool
     */
    public function isSuccessful($message, $digest, $statusCode)
    {
        $status = $this->parseStatusCode($statusCode);

        return sha1($message) == $digest && $status == 'Success';
    }

    /**
     * Check if a transaction fails.
     *
     * @param $statusCode
     * @return bool
     */
    public function fails($statusCode)
    {
        return $this->parseStatusCode($statusCode) == 'Failure';
    }

    /**
     * Get the string representation of a transaction's status code.
     *
     * @param $code
     * @return string
     */
    public function parseStatusCode($code)
    {
        $status = '';

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
            default:
                return 'Failed';
                break;
        }
    }

    /**
     * Get the string representation of a transaction's error code.
     *
     * @param $code
     * @return string
     */
    public function parseErrorCode($code)
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

}
<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\Transaction\TransactionFactory;

class TransactionService
{

    /**
     * @var DragonpayClient
     */
    private $client;

    private $transaction;

    public function __construct(DragonpayClient $client, $webService = 'REST')
    {
        $this->client = $client;
        $this->transaction = TransactionFactory::create($webService, $client);
    }

    public function inquire($transactionId)
    {
        $credentials = [
            'merchantId'       => $this->client->getMerchantId(),
            'merchantPassword' => $this->client->getMerchantPassword(),
            'transactionId'    => $transactionId
        ];

        $code = $this->transaction->inquire($credentials);

        return $this->parseTransactionStatusCode($code);
    }

    public function cancel($transactionId)
    {
        $credentials = [
            'merchantId'       => $this->client->getMerchantId(),
            'merchantPassword' => $this->client->getMerchantPassword(),
            'transactionId'    => $transactionId
        ];

        $code = $this->transaction->cancel($credentials);

        return $this->parseTransactionCancellationStatusCode($code);
    }

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

    private function parseTransactionCancellationStatusCode($code)
    {
        if ($code == 0) {
            return 'Success';
        }

        return 'Failed';

    }

}
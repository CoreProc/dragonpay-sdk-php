<?php namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Classes\URLGenerator;

class DragonpayTransaction
{

    /**
     * @var DragonpayService
     */
    private $service;

    private $urlGenerator;

    public function __construct(DragonpayService $service)
    {
        $this->service = $service;
        $this->urlGenerator = new URLGenerator();
    }

    /**
     * @param $transactionId
     * @return mixed
     */
    public function getTransactionInquiryUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->service->getMerchantId(),
            $this->service->getMerchantPassword(),
            $transactionId,
            'GETSTATUS'
        );
    }

    /**
     * Get the generated URL for the cancellation of a transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function getTransactionCancellationUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->service->getMerchantId(),
            $this->service->getMerchantPassword(),
            $transactionId,
            'VOID'
        );
    }

    /**
     * Get the status of a transaction.
     *
     * @param $statusCode
     * @return string
     */
    public function getTransactionStatus($statusCode)
    {
        $status = '';

        switch ($statusCode) {
            case 'S':
                $status = 'success';
                break;
            case 'F':
                $status = 'failure';
                break;
            case 'P':
                $status = 'pending';
                break;
            case 'U':
                $status = 'unknown';
                break;
            case 'R':
                $status = 'refund';
                break;
            case 'K':
                $status = 'chargeback';
                break;
            case 'V':
                $status = 'void';
                break;
            case 'A':
                $status = 'authorized';
                break;
        }

        return $status;
    }

    /**
     * Get the status of a transaction cancellation.
     *
     * @param $statusCode
     * @return string
     */
    public function getTransactionCancellationStatus($statusCode)
    {
        switch ($statusCode) {
            case 0:
                return 'success';
                break;
            default:
                return 'failed';
                break;
        }
    }

    /**
     * Determine if order is ready for shipping.
     *
     * @param $message
     * @param $digest
     * @param $status
     * @return bool
     */
    public function isValidForShipping($message, $digest, $status)
    {
        return sha1($message) == $digest && $status == 'success';
    }
}
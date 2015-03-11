<?php namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Classes\URLGenerator;

class DragonpayService
{

    /**
     * @var URLGenerator
     */
    private $urlGenerator;

    private $merchantId;

    private $secretKey;

    private $merchantPassword;

    public function __construct($merchantId, $secretKey, $merchantPassword)
    {
        $this->urlGenerator = new URLGenerator();
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->merchantPassword = $merchantPassword;
    }

    /**
     * Get the generated URL for redirecting to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function getUrl(array $data)
    {
        $data['merchantId'] = $this->merchantId;
        $data['secretKey'] = $this->secretKey;

        return $this->urlGenerator->generate($data);
    }

    /**
     * Get the generated URL for inquiring the status of a transaction.
     *
     * @return string
     */
    public function getTransactionInquiryUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->merchantId,
            $this->merchantPassword,
            $transactionId,
            'GETSTATUS'
        );
    }

    /**
     * Get the generated URL for the cancellation of a transaction.
     *
     * @param $merchantId
     * @param $merchantPwd
     * @param $txnId
     * @return string
     */
    public function getTransactionCancellationUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->merchantId,
            $this->merchantPassword,
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



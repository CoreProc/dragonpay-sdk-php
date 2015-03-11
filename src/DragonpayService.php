<?php namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Classes\URLGenerator;

class DragonpayService
{

    /**
     * @var URLGenerator
     */
    private $urlGenerator;

    public function __construct()
    {
        $this->urlGenerator = new URLGenerator();
    }

    /**
     * Get the generated URL for redirecting to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function getUrl(array $data)
    {
        return $this->urlGenerator->generate($data);
    }

    /**
     * Get the generated URL for inquiring the status of a transaction.
     *
     * @param $merchantId
     * @param $merchantPwd
     * @param $txnId
     * @return string
     */
    public function getTxnInquiryUrl($merchantId, $merchantPwd, $txnId)
    {
        return $this->urlGenerator->generateTxnQueryUrl($merchantId, $merchantPwd, $txnId, 'GETSTATUS');
    }

    /**
     * Get the generated URL for the cancellation of a transaction.
     *
     * @param $merchantId
     * @param $merchantPwd
     * @param $txnId
     * @return string
     */
    public function getTxnCancellationUrl($merchantId, $merchantPwd, $txnId)
    {
        return $this->urlGenerator->generateTxnQueryUrl($merchantId, $merchantPwd, $txnId, 'VOID');
    }

    /**
     * Get the status of a transaction.
     *
     * @param $statusCode
     * @return string
     */
    public function getTxnStatus($statusCode)
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
    public function getTxnCancellationStatus($statusCode)
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



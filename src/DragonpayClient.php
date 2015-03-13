<?php

namespace Coreproc\Dragonpay;

use Katzgrau\KLogger\Logger;

class DragonpayClient
{

    private $merchantId;

    private $secretKey;

    private $merchantPassword;

    private $logging = false;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param $merchantId
     * @param $secretKey
     * @param $merchantPassword
     * @param bool $logging
     * @param null|string $logDirectory
     */
    public function __construct($merchantId, $secretKey, $merchantPassword, $logging = false, $logDirectory = null)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->merchantPassword = $merchantPassword;

        if ($logging == true) {
            if ($logDirectory !== null && is_dir($logDirectory)) {
                $this->logging = true;
                $this->logger = new Logger($logDirectory);
            } else {
                die('Please make sure that you set a valid log directory to enable logging.');
            }
        }
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

    public function isLoggingEnabled()
    {
        return $this->logging;
    }

    public function getLogger()
    {
        return $this->logger;
    }

}



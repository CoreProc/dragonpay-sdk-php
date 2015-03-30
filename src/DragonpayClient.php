<?php

namespace Coreproc\Dragonpay;

use Katzgrau\KLogger\Logger;

class DragonpayClient
{

    private $merchantId;

    private $merchantPassword;

    private $logging = false;

    private $logger;

    public function __construct(array $credentials, $logging = false, $logDirectory = null)
    {
        $this->merchantId = $credentials['merchantId'];
        $this->merchantPassword = $credentials['merchantPassword'];
        $this->setLogger($logging, $logDirectory);
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return mixed
     */
    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

    /**
     * @param $logging
     * @param $logDirectory
     */
    private function setLogger($logging, $logDirectory)
    {
        if ($logging == true && ! is_dir($logDirectory)) {
            die('Please set a valid directory in order to enable logging.');
        }

        $this->logging = true;
        $this->logger = new Logger($logDirectory);
    }

    /**
     * @return bool
     * @TODO Make a logger class?
     */
    public function isLoggingEnabled()
    {
        return $this->logging;
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

}
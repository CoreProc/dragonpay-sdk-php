<?php

namespace Coreproc\Dragonpay;

use Katzgrau\KLogger\Logger;

class DragonpayClient
{

    /**
     * @var string Merchant's ID
     */
    private $merchantId;

    /**
     * @var string Merchant's Password
     */
    private $merchantPassword;

    /**
     * @var bool Enable or disable logging
     */
    private $logging = false;

    /**
     * @var null|Logger
     */
    private $logger;

    /**
     * @param array $credentials
     * @param bool $logging
     * @param null|string $logDirectory
     */
    public function __construct(array $credentials, $logging = false, $logDirectory = null)
    {
        $this->merchantId = $credentials['merchantId'];
        $this->merchantPassword = $credentials['merchantPassword'];
        $this->setLogger($logging, $logDirectory);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return string
     */
    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

    /**
     * Set the logger if logging is set to true.
     *
     * @param bool $logging
     * @param string $logDirectory
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
     * Check if logging is enabled.
     *
     * @return bool
     * @TODO Make a logger class?
     */
    public function isLoggingEnabled()
    {
        return $this->logging;
    }

    /**
     * @return Logger|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

}
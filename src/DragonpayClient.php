<?php

namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Exceptions\ValidationException;
use Exception;
use Katzgrau\KLogger\Logger;

class DragonpayClient
{

    /**
     * @var string Merchant ID
     */
    private $merchantId;

    /**
     * @var string Merchant Password
     */
    private $merchantPassword;

    /**
     * @var bool Enable/disable logging
     */
    private $logging = false;

    /**
     * @var null|Logger
     */
    private $logger;

    /**
     * @param array $credentials Merchant Credentials
     * @param bool $logging
     * @param null|string $logDirectory
     * @throws ValidationException
     */
    public function __construct(array $credentials, $logging = false, $logDirectory = null)
    {
        if (empty($credentials['merchantId']) || empty($credentials['merchantPassword'])) {
            throw new ValidationException('Please set the Merchant ID or password.');
        };

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

    /**
     * Set the logger if logging is set to true.
     *
     * @param bool $logging
     * @param string $logDirectory
     * @throws Exception
     */
    private function setLogger($logging, $logDirectory)
    {
        if ($logging == true && ! is_dir($logDirectory)) {
            throw new Exception('Please set a valid directory in order to enable logging.');
        }

        $this->logging = true;
        $this->logger = new Logger($logDirectory);
    }

}
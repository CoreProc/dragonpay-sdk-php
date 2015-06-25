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
     * @var bool Enable/disable testing
     */
    private $testing = false;

    /**
     * @param array $config
     * @throws Exception
     * @throws ValidationException
     */
    public function __construct(array $config)
    {
        if (empty($config['merchantId']) || empty($config['merchantPassword'])) {
            throw new ValidationException('Please set the Merchant ID and password.');
        };

        $this->merchantId = $config['merchantId'];
        $this->merchantPassword = $config['merchantPassword'];

        if ( ! isset($config['logging'])) {
            $config['logging'] = false;
        }

        if ( ! isset($config['logDirectory'])) {
            $config['logDirectory'] = null;
        }

        $this->setLogger($config['logging'], $config['logDirectory']);

        if (isset($config['testing']) && $config['testing'] === true) {
            $this->testing = true;
        }
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
     * @return bool
     */
    public function isTesting()
    {
        return $this->testing;
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
        if ($logging === true) {
            if ( ! is_dir($logDirectory)) {
                throw new Exception('Please set a valid directory in order to enable logging.');
            }
            $this->logging = true;
            $this->logger = new Logger($logDirectory);
        }

    }

}
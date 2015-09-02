<?php

namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Transaction\RestTransaction;
use Coreproc\Dragonpay\Transaction\TransactionInterface;
use Coreproc\Dragonpay\UrlGenerator\RestUrlGenerator;
use Coreproc\Dragonpay\UrlGenerator\UrlGeneratorInterface;
use Exception;
use Katzgrau\KLogger\Logger;
use Psr\Log\LoggerInterface;

class DragonpayApi
{

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $merchantPassword;

    /**
     * @var RestUrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var RestTransaction
     */
    protected $transaction;

    /**
     * @var Logger
     */
    protected $log;

    /**
     * @var bool
     */
    protected $logging = false;

    /**
     * @param array $config
     * @param UrlGeneratorInterface|null $urlGenerator
     * @param TransactionInterface|null $transaction
     * @param LoggerInterface $log
     * @throws Exception
     */
    public function __construct(
        array $config,
        UrlGeneratorInterface $urlGenerator = null,
        TransactionInterface $transaction = null,
        LoggerInterface $log = null
    )
    {
        if ( ! isset($config['merchantId'])) {
            throw new Exception('Required: Please set the "merchantId" key.');
        }

        if ( ! isset($config['merchantPassword'])) {
            throw new Exception('Required: Please set the "merchantPassword" key.');
        }

        if ( ! isset($config['testing'])) {
            $config['testing'] = false;
        }

        if (isset($config['logging']) && $config['logging'] == true) {
            if ( ! isset($config['logDirectory'])) {
                throw new Exception('Required: Please set the "logDirectory" key.');
            }

            $this->logging = true;
            $this->log = $log ?: new Logger($config['logDirectory']);
        }

        $this->merchantId = $config['merchantId'];
        $this->merchantPassword = $config['merchantPassword'];
        $this->urlGenerator = $urlGenerator ?: new RestUrlGenerator($config['testing']);
        $this->transaction = $transaction ?: new RestTransaction(null, $config['testing']);
    }

    /**
     * Get the generated URL to Dragonpay Payment Switch.
     *
     * @param array $params
     * @param null $paymentChannel
     * @return string
     */
    public function getUrl(array $params, $paymentChannel = null)
    {
        $url = $this->urlGenerator->generate(
            array_merge($params, $this->getMerchantCredentials()),
            $paymentChannel
        );

        if ($this->loggingIsEnabled()) {
            $this->log->info("Generated URL to Dragonpay Payment Switch. URL: $url");
        }

        return $url;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function transactionIsValid(array $params)
    {
        $isValid = $this->transaction->isValid(array_merge($params, $this->getMerchantCredentials()));

        if ($this->loggingIsEnabled()) {
            $valid = $isValid ? 'Valid' : 'Invalid';
            $this->log->info("Checked if Dragonpay request params are valid. (Transaction ID: {$params['transactionId']}) Result: $valid");
        }

        return $isValid;
    }

    /**
     * @param array $params
     * @return string
     */
    public function inquire(array $params)
    {
        $status = $this->transaction->inquire(array_merge($params, $this->getMerchantCredentials()));

        if ($this->loggingIsEnabled()) {
            $this->log->info("Inquired for the status of transaction ID {$params['transactionId']}. Status: {$status}");
        }

        return $status;
    }

    /**
     * @param array $params
     * @return string
     */
    public function cancel(array $params)
    {
        $status = $this->transaction->cancel(array_merge($params, $this->getMerchantCredentials()));

        if ($this->loggingIsEnabled()) {
            $this->log->info("Cancelled the transaction with ID: {$params['transactionId']}. Status: {$status}");
        }

        return $status;
    }

    /**
     * @return array
     */
    protected function getMerchantCredentials()
    {
        return [
            'merchantId'       => $this->merchantId,
            'merchantPassword' => $this->merchantPassword,
        ];
    }

    /**
     * @return bool
     */
    protected function loggingIsEnabled()
    {
        return $this->logging;
    }

}

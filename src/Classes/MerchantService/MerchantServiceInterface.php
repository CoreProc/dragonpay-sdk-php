<?php

namespace Coreproc\Dragonpay\Classes\MerchantService;

interface MerchantServiceInterface
{

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param $transactionId
     * @return string
     */
    public function inquire(array $credentials, $transactionId);

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param $transactionId
     * @return string
     */
    public function cancel(array $credentials, $transactionId);

}
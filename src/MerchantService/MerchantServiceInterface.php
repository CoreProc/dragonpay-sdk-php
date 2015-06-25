<?php

namespace Coreproc\Dragonpay\MerchantService;

interface MerchantServiceInterface
{

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param $transactionId
     * @param $testing
     * @return string
     */
    public function inquire(array $credentials, $transactionId, $testing);

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param $transactionId
     * @param $testing
     * @return string
     */
    public function cancel(array $credentials, $transactionId, $testing);

}
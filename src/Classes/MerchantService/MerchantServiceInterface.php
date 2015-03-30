<?php

namespace Coreproc\Dragonpay\Classes\MerchantService;

interface MerchantServiceInterface
{

    /**
     * Inquire for a transaction's status.
     *
     * @param array $credentials
     * @param $transactionId
     * @return mixed
     */
    public function inquire(array $credentials, $transactionId);

    /**
     * Cancel a transaction.
     *
     * @param array $credentials
     * @param $transactionId
     * @return mixed
     */
    public function cancel(array $credentials, $transactionId);

}
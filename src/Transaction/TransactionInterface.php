<?php

namespace Coreproc\Dragonpay\Transaction;

interface TransactionInterface
{

    /**
     * @param array $params
     * @return bool
     */
    public function isValid(array $params);

    /**
     * @param array $params
     * @return string
     */
    public function inquire(array $params);

    /**
     * @param array $params
     * @return string
     */
    public function cancel(array $params);

}
<?php

namespace Coreproc\Dragonpay\Classes\Transaction;

interface TransactionInterface
{

    public function inquire(array $credentials);

    public function cancel(array $credentials);

}
<?php

use Coreproc\Dragonpay\Transaction;

require 'Client.php';

$transaction = new Transaction($client);

var_dump($transaction->inquire('mockPending2'));

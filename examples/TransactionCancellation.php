<?php

use Coreproc\Dragonpay\Transaction;

require 'Client.php';

$transaction = new Transaction($client);

var_dump($transaction->cancel('11245678901234567899'));

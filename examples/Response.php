<?php

require 'Client.php';

use Coreproc\Dragonpay\Transaction;

$params = [
    'transactionId'     => '12r8761625f',
    'referenceNumber'   => 'AGB5GD35',
    'status'            => 'S',
    'message'           => '20150414203713',
    'digest'            => '00ee60bb1f1539e333a84828b0272ec060885cb8',
];

$transaction = new Transaction($client);
var_dump($transaction->isSuccessful($params));

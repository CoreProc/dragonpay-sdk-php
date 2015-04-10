<?php

require 'Client.php';

use Coreproc\Dragonpay\Transaction;

$transaction = new Transaction($client, 'SOAP');

$params = [
    'transactionId' => '11245678901234567823',
    'firstName'     => 'John',
    'lastName'      => 'Doe',
    'address1'      => 'Address 1',
    'address2'      => 'Address 2',
    'city'          => 'Quezon City',
    'state'         => 'State',
    'country'       => 'Philippines',
    'zipCode'       => '1116',
    'telNo'         => '(02)9123456',
    'email'         => 'john@example.com'
];

$status = $transaction->sendBillingInformation($params);

var_dump($status);

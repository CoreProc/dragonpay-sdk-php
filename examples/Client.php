<?php

require_once '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayClient;

$credentials = [
   'merchantId'       => 'merchant-id',
   'merchantPassword' => 'merchant-password',
];

$logging = true;
$logDirectory = 'logs/';

$client = new DragonpayClient($credentials, $logging, $logDirectory);
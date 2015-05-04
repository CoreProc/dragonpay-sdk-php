<?php

require 'vendor/autoload.php';
require 'examples/Client.php';

use GuzzleHttp\Transaction;

$transaction = new \Coreproc\Dragonpay\Transaction($client);

var_dump($transaction->isSuccessful($_GET));

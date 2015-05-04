<?php

require 'vendor/autoload.php';
require 'examples/Client.php';

use Coreproc\Dragonpay\Transaction;

$transaction = new Transaction($client);

$params = [
    'transactionId' => $_GET['txnid'],
    'referenceNumber' => $_GET['refno'],
    'status' => $_GET['status'],
    'message' => $_GET['message'],
    'digest' => $_GET['digest'],

];

var_dump($result);

if ($result == true) {
    // Perform necessary db updates/whatever back-end process required
	var_dump($result);
} else {
	var_dump($transaction->inquire($params['transactionId']));
}

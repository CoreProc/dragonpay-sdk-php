<?php

require 'Client.php';

use Coreproc\Dragonpay\Checkout;

$checkout = new Checkout($client, 'SOAP');

// REST service specific parameters:
//$params = [
//    'transactionId' => 'transaction-id',
//    'amount'        => '20000.00',
//    'currency'      => 'PHP',
//    'description'   => 'Playstation 4',
//    'email'         => 'john@example.com',
//];

// SOAP service specific required parameters:
$params = [
    'transactionId' => 'transaction-id-12323',
    'amount'        => '20000.99',
    'currency'      => 'PHP',
    'description'   => 'Playstation 4',
];

$filter = null;
//$filter = 'online_banking';

var_dump($checkout->getUrl($params, $filter));

//$checkout->redirect($params, $filter);
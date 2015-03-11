<?php

require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayService;
use Coreproc\Dragonpay\DragonpayTransaction;

$merchantId = '123456';
$secretKey = 'secret';
$password = 'password';

$service = new DragonpayService($merchantId, $secretKey, $password);

# Generating URL to Payment Switch

// Form data from merchant site
$data = array(
    'transactionId' => '987654',
    'amount'        => 1234.56,
    'ccy'           => 'PHP',
    'description'   => 'Lorem ipsum dolor amet.',
    'email'         => 'john@example.com',
);

// Get the generated URL
$url = $service->getUrl($data);

echo("URL to Dragonpay PS: " . $url . '<br>');

# Handling response from PS API

// Request data from PS API
// params: txnid, refno, status, message, digest
$data2 = array(
    'transactionId'   => '987654',
    'refno'   => '123456',
    'status'  => 'S', // Result of payment
    'message' => 'Blah blah', // Success: PG Trans. Refno, Failure: Error codes, Pending: Refno to complete funding
    'digest'  => '12345678987654321',
);

// Pass secret key from merchant
$data2['secretKey'] = 'secret';

$transaction = new DragonpayTransaction($service);

// Get string representation of status
$status = $transaction->getTransactionStatus($data2['status']);

// Check if transaction is successful
if ($transaction->isValidForShipping($data2['message'], $data2['digest'], $status)) {
    echo 'TRANSACTION STATUS: ' . $status . '<br>';
}

// Handle other status here
echo 'TRANSACTION STATUS: ' . $status . '<br>';
echo '<hr>';

# Inquire transaction status

// Required params
$transactionId = 12345;

// Get generated URL from inquiring transaction status from PS.
$url = $transaction->getTransactionInquiryUrl($transactionId);

echo 'TRANSACTION INQUIRY URL: ' . $url;
echo '<br>';

// Get status
// Request data from PS
$status = 'S';

echo 'TRANSACTION STATUS: ' . $transaction->getTransactionStatus($status) . '<br>';
echo '<hr>';

# Cancellation of transaction
$url = $transaction->getTransactionCancellationUrl($transactionId);

echo 'TRANSACTION CANCELLATION URL: ' . $url . '<br>';

// Get status
// Request data from PS
$status = 0;
echo 'CANCELLATION STATUS: ' . $transaction->getTransactionCancellationStatus($status);
echo '<hr>';

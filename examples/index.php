<?php

require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayService;

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
    'txnid'   => '987654',
    'refno'   => '123456',
    'status'  => 'S', // Result of payment
    'message' => 'Blah blah', // Success: PG Trans. Refno, Failure: Error codes, Pending: Refno to complete funding
    'digest'  => '12345678987654321',
);

// Pass secret key from merchant
$data2['secretkey'] = 'secret';

// Get string representation of status
$status = $service->getTransactionStatus($data2['status']);

// Check if transaction is successful
if ($service->isValidForShipping($data2['message'], $data2['digest'], $status)) {
    echo 'TRANSACTION STATUS:' . $status . '<br>';
}

// Handle other status here
echo 'TRANSACTION STATUS:' . $status . '<br>';
echo '<hr>';

# Inquire transaction status

// Required params
$merchantId = 12345;
$merchantPwd = 'secret';
$txnid = 12345;

// Get generated URL from inquiring transaction status from PS.
$url = $service->getTransactionInquiryUrl($merchantId, $merchantPwd, $txnid);

echo 'TRANSACTION INQUIRY URL :' . $url;
echo '<br>';

// Get status
// Request data from PS
$status = 'S';

echo 'TRANSACTION STATUS:' . $service->getTransactionStatus($status) . '<br>';
echo '<hr>';

# Cancellation of transaction
$url = $service->getTransactionCancellationUrl($merchantId, $merchantPwd, $txnid);

echo 'TRANSACTION CANCELLATION URL:' . $url . '<br>';

// Get status
// Request data from PS
$status = 0;
echo 'CANCELLATION STATUS:' . $service->getTransactionCancellationStatus($status);
echo '<hr>';

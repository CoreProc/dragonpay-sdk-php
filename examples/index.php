<?php

require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\Checkout;
use Coreproc\Dragonpay\Classes\Transaction;

$merchantId = '123456';
$secretKey = 'secret';
$password = 'password';

$client = new DragonpayClient($merchantId, $secretKey, $password);

# Generating URL to Payment Switch
$checkout = new Checkout($client);

// Form data from merchant site
$data = array(
    'transactionId' => '987654',
    'amount'        => 1234.56,
    'currency'      => 'PHP',
    'description'   => 'Lorem ipsum dolor amet.',
    'email'         => 'john@example.com',
);

// Get the generated URL
$url = $checkout->getUrl($data);

echo("URL to Dragonpay PS: " . $url . '<br>');

# Handling response from PS API

// Request data from PS API
// params: txnid, refno, status, message, digest
$data2 = array(
    'transactionId' => '987654',
    'refno'         => '123456',
    'status'        => 'S', // Result of payment
    'message'       => 'Blah blah', // Success: PG Trans. Refno, Failure: Error codes, Pending: Refno to complete funding
    'digest'        => '12345678987654321',
);

$transaction = new Transaction($client);

// Get string representation of status
$status = $transaction->getStatus($data2['status']);

// Check if transaction is successful
if ($transaction->isSuccessful($data2['message'], $data2['digest'], $status)) {
    // Proceed to shipping
    echo 'TRANSACTION STATUS: ' . $status . '<br>';
} else {
    // Handle other status here
    // Abort processing?
    echo 'TRANSACTION STATUS: ' . $status . '<br>';

    // If status is failed, message would be an error code
    if ($status == 'failed') {
        $error = $transaction->getError($data2['message']);
        echo 'Error in transaction: ' . $error;
    }
}

echo '<hr>';

# Inquire transaction status

// Required params
$transactionId = 12345;

// Get generated URL from inquiring transaction status from PS.
$url = $transaction->getInquiryUrl($transactionId);

echo 'TRANSACTION INQUIRY URL: ' . $url;
echo '<br>';

// Get status
// Request data from PS
$status = 'S';

echo 'TRANSACTION STATUS: ' . $transaction->getStatus($status) . '<br>';
echo '<hr>';

# Cancellation of transaction
$url = $transaction->getCancellationUrl($transactionId);

echo 'TRANSACTION CANCELLATION URL: ' . $url . '<br>';

// Get status
// Request data from PS
$status = 0;
echo 'CANCELLATION STATUS: ' . $transaction->getCancellationStatus($status);
echo '<hr>';

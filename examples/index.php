<?php

# Setting up the Client

require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayClient;

$merchantId = '123456';
$secretKey = 'secret';
$password = 'password';

$client = new DragonpayClient($merchantId, $secretKey, $password);

# Generating URL to Payment Switch

use Coreproc\Dragonpay\Classes\Checkout;

$checkout = new Checkout($client);

// Form data from merchant site
$data = array(
    'transactionId' => '12345',
    'amount'        => 20499.99,
    'currency'      => 'PHP',
    'description'   => 'PS4',
    'email'         => 'john@example.com',
);

// Get the generated URL
$url = $checkout->getUrl($data);

echo("URL to Dragonpay PS: " . $url . '<br><hr>');

# Handling response from PS API

use Coreproc\Dragonpay\Classes\Transaction;

// Request data from PS API
// params: txnid, refno, status, message, digest
$data2 = array(
    'transactionId' => '987654',
    'refno'         => '123456',
    'status'        => 'K', // Result of payment
    'message'       => 'Blah blah', // Success: PG Trans. Refno, Failure: Error codes, Pending: Refno to complete funding
    'digest'        => '12345678987654321',
);

$transaction = new Transaction($client);

// Check if transaction is successful
if ($transaction->isSuccessful($data2['message'], $data2['digest'], $data2['status'])) {
    // Proceed to shipping
    echo 'Transaction is successful. Proceed to shipment of the order';
} else {
    // Abort processing?

    // If status is failed, message would be an error code
    if ($transaction->fails($data2['status'])) {
        $error = $transaction->parseErrorCode($data2['message']);
        echo 'Error in transaction: ' . $error;
    }

    // Handle other status here
    $status = $transaction->parseStatusCode($data2['status']);

    echo 'Transaction status: ' . $status;
}

echo '<hr>';

# Transaction status inquiry

$transaction = new Transaction($client);

$transactionId = 12345;

$status = $transaction->statusInquiry($transactionId);

echo 'Transaction status: ' . $status . '<br>' . '<hr>';

# Transaction cancellation

$transactionId = 12345;

$status = $transaction->cancel($transactionId);

echo 'Transaction cancellation status: ' . $status;

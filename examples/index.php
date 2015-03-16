<?php

# Setting up the Client

require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayClient;

// Merchant Credentials
$merchantId = '123456';
$secretKey = 'secret';
$password = 'password';

// Enable logging
$logging = true;
$logDirectory = 'logs/';

$client = new DragonpayClient($merchantId, $secretKey, $password, $logging, $logDirectory);

# Getting the URL to Payment Switch

use Coreproc\Dragonpay\Classes\Checkout;
use Coreproc\Dragonpay\Classes\ValidationException;

$checkout = new Checkout($client);

// Form data from merchant site
//
// Params:
// merchantid - Unique code assigned to Merchant. (Getter available from the client)
// txnid - A unique ID identifying this specific transaction from the merchant side.
// amount - The amount to get from the end-user (XXXX.XX)
// ccy - The currency of the amout (PHP/USD)
// description - A brief description of what the payment is for
// email - E-mail address of the customer
// digest - A sha1 digest of all the params along with the secret key
// param1 & param2 - (Optional) Value to be posted back to the merchant url when completed
$data = array(
    'transactionId' => '12345',
    'amount'        => 12345,
    'currency'      => 'PHP',
    'description'   => 'PS4',
    'email'         => 'john@example.com',
);

// Get the generated URL
try {
    $url = $checkout->getUrl($data);
} catch (ValidationException $e) {
    var_dump($e->getErrors());
}

echo("URL to Dragonpay PS: " . $url . '<br><hr>');

# Handling response from PS API

use Coreproc\Dragonpay\Classes\Transaction;

// Request data from PS API
//
// Params:
// txnid - Unique ID identifying this specific transaction from the merchant side.
// refno - Common reference number identifying this specific transaction from the PS side.
// status - Result of the payment. (Refer to Appendix 3 of the docs for codes)
// message - If status is SUCCESS, this should be the PG transaction reference number.
// if status is FAILURE, return one of the error codes (refer to Appendix 2 of the docs)
// if status is PENDING, this would be a reference number to complete the funding.
// digest - sha1 checksum digest of the params along with the secret key.
$data2 = array(
    'transactionId' => '123456',
    'refno'         => '7891011',
    'status'        => 'F', // Failure
    'message'       => 101, // Invalid payment gateway id
    'digest'        => '12345678987654321',
);

$transaction = new Transaction($client);

// Check if transaction is successful
if ($transaction->isSuccessful($data2)) {
    // Proceed to shipping
    echo 'Transaction is successful. Proceed to shipment of the order';
} else {
    // Abort processing

    // If status is failed, message would be an error code
    if ($transaction->fails($data2)) {
        $error = $transaction->getError();
        echo 'Error in transaction: ' . $error . '<br>';
    }

    // Handle other status here
    $status = $transaction->getStatus();

    echo 'Transaction status: ' . $status;
}

echo '<hr>';

# Transaction status inquiry

$transaction = new Transaction($client);

$transactionId = 12345;

$status = $transaction->statusInquiry($transactionId);

echo 'Transaction status: ' . $status . '<br>' . '<hr>';

# Transaction cancellation

// Enable logging
$logging = true;
$logDirectory = 'logs/';

$transaction = new Transaction($client);

$transactionId = 12345;

$status = $transaction->cancel($transactionId);

echo 'Transaction cancellation status: ' . $status;

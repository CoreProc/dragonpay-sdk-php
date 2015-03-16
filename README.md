Dragonpay Payment Switch API - PHP Library
========

A PHP Library for consuming Dragonpay's Payment Switch API.

## Quick start

### Required setup

The easiest way to install this library is via Composer.

Create a `composer.json` file and enter the following:

    {
        "require": {
            "coreproc/dragonpay-sdk": "dev-master"
        }
    }

If you haven't yet downloaded your composer file, you can do so by executing the following in your command line:

    curl -sS https://getcomposer.org/installer | php

Once you've downloaded the composer.phar file, continue with your installation by running the following:

    php composer.phar install
    
## Usage

### Setting up the Client

    require '../vendor/autoload.php';
    
    use Coreproc\Dragonpay\DragonpayClient;
    
    // Merchant Credentials
    $merchantId = '123456';
    $secretKey = 'secret';
    $password = 'password';
    
    // Enable logging
    $logging = true; // true/false. false by default
    $logDirectory = 'logs/'; // can be left off if logging is false
    
    $client = new DragonpayClient($merchantId, $secretKey, $password, $logging, $logDirectory);
    
### Getting the URL to the Payment Switch API

    use Coreproc\Dragonpay\Classes\Checkout;
    
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
        'amount'        => 20499.99,
        'currency'      => 'PHP',
        'description'   => 'PS4',
        'email'         => 'john@example.com',
    );

    // Get the generated URL
    $url = $checkout->getUrl($data);

### Handling the response

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
        'transactionId' => '987654',
        'refno'         => '123456',
        'status'        => 'F', // Failure
        'message'       => 101, // Invalid payment gateway ID
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

### Transaction status inquiry

    $transaction = new Transaction($client);

    $transactionId = 12345;

    $status = $transaction->statusInquiry($transactionId);

    echo 'Transaction status: ' . $status . '<br>' . '<hr>';

### Transaction cancellation

    $transaction = new Transaction($client);
    
    $transactionId = 12345;

    $status = $transaction->cancel($transactionId);

    echo 'Transaction cancellation status: ' . $status;

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
    
    $merchantId = '123456';
    $secretKey = 'secret';
    $password = 'password';
    
    $client = new DragonpayClient($merchantId, $secretKey, $password);
    
### Getting the URL to Payment Switch

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


### Handling the response

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

### Transaction status inquiry

    $transaction = new Transaction($client);

    $transactionId = 12345;

    $status = $transaction->statusInquiry($transactionId);

    echo 'Transaction status: ' . $status . '<br>' . '<hr>';

### Transaction cancellation

    $transactionId = 12345;

    $status = $transaction->cancel($transactionId);

    echo 'Transaction cancellation status: ' . $status;

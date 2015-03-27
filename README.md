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

### Setting up the Dragonpay Client

    require '../vendor/autoload.php';

    // Set the merchant credentials
    $credentials = [
        'merchantId'        => 'merchant-id',
        'merchantPassword'  => 'merchant-password',
    ];

    $logging = true; // Optional: true/false; false by default
    $logDirectory = 'logs'; // Leave off if logging is false

    // Instantiate the client
    $client = new \Coreproc\Dragonpay\DragonpayClient($credentials);

### Checkout

    // Pass in the client instance and preferred web service (REST/SOAP); REST by default.
    $checkout = new \Coreproc\Dragonpay\Classes\Checkout($client);

    // Transaction specific parameters
    // REST service params
    $params = [
        'transactionId' => '22345678912345678907',
        'amount'        => 20000.99,
        'currency'      => 'PHP',
        'description'   => 'Playstation 4',
        'email'         => 'john@example.com',
    ];

    // SOAP service params
    //$params [
    //    'merchantTxnId' => '12345672912345672907',
    //    'amount'        => 20000.99,
    //    'ccy'           => 'PHP',
    //    'description'   => 'Playstation 4',
    //];

    // For getting the URL to PS
    //$url = $checkout->getURL($params);
    //var_dump($url);

    // For redirecting to PS
    $checkout->redirect($params);
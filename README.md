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

    use Coreproc\Dragonpay\DragonpayClient;

Set the merchant credentials:

    $credentials = [
        'merchantId'        => 'merchant-id',
        'merchantPassword'  => 'merchant-password',
    ];
    
Logging is optional. It's set to false by default and can be left off when instantiating the client like so:

    $client = new DragonpayClient($credentials);
    
When set to true, a third parameter is required for the logging directory.

    $logging = true;
    $logDirectory = 'logs';

Instantiate the client:

    $client = new DragonpayClient($credentials, $logging, $logDirectory);

### Checkout

    use Coreproc\Dragonpay\Checkout;

REST is the default web service used by the client and can be left off when instantiating the class like so:
 
    $checkout = new Checkout($client);
    
Using the SOAP Web Service:

    $checkout = new Checkout($client, 'SOAP');

Transaction parameters from the merchant site:

REST service specific required parameters:
    
    $params = [
        'transactionId' => 'transaction-id',
        'amount'        => 20000.00,
        'currency'      => 'PHP',
        'description'   => 'Playstation 4',
        'email'         => 'john@example.com',
    ];

SOAP service specific required parameters:

    $params = [
        'transactionId' => 'transaction-id',
        'amount'        => 20000.99,
        'currency'      => 'PHP',
        'description'   => 'Playstation 4',
    ];

For getting the URL to Payment Switch:

Returns a URL for redirecting to the Payment Switch manually.
    
    $url = $checkout->getUrl($params);

For redirecting to the generated URL:

    $checkout->redirect($params);
    
Filtering Payment Channels

There may be instances wherein the merchant would want to filter the payment
channels that they want to appear in Dragonpay’s payment selection page, or they
may want to skip the Dragonpay page altogether and go straight to the payment
details for a specific channel.

The merchant can implement this by providing a second parameter to the `getUrl` and `redirect` methods of the class `Checkout` respectively.
 
For a showing only a specific payment channel:

    Online Banking: online_banking
    Over-the-Counter Banking and ATM: otc_banking_atm
    Over-the-Counter non-Bank: otc_non_bank
    PayPal: paypal
    Credit Cards: credit_card 
    Mobile (Gcash): mobile
    International OTC: international_otc

For redirecting directly to a payment channel:

    Globe Gcash: gcash_direct
    Credit Cards: credit_card_direct
    PayPal: paypal_direct
    
Pass on the filter like so:

    $filter = 'online_banking';
    
    $checkout = new Checkout($client, $filter);
    
### Handling the Response

    use Coreproc\Dragonpay\Transaction;

Pass in the client instance:

    $transaction = new Transaction($client);

Required request data from Dragonpay Payment Switch:

    $params = [
        'transactionId'   => $_GET['txnid'],
        'referenceNumber' => $_GET['refNo'],
        'status'          => $_GET['status']
        'message'         => $_GET['message'],
        'digest'          => $_GET['digest']
    ];
    
Checking if the transaction is successful:

Returns a boolean (true or false)

    $transaction->isSuccessful($params);
    
### Support functions

The Payment Switch provides some supplementary functions allowing merchants to more tightly
integrate and automate their systems. These functions are available using the REST or SOAP web service.

### Transaction Inquiry

The merchant can inquire the status of a transaction by using this function.

    use Coreproc\Dragonpay\Transaction;
    
The `Transaction` class uses the REST web service as a default, allowing for leaving off of the web service parameter like so:

    $transaction = new Transaction($client);
    
Using the SOAP web service:

    $transaction = new Transaction($client, 'SOAP');
    
Returns the status of the transaction (string)

    $transactionId = 'transaction-id';
    
    $status = $transaction->inquire($transactionId);
    
### Cancellation of Transaction

The merchant can cancel a pending transaction by using this function.

Returns the status of the transaction cancellation (string)
 
    use Coreproc\Dragonpay\Transaction;
    
    $transaction = new Transaction($client);
    
    $transactionId = 'transaction-id';
    
    $status = $transaction->cancel($transactionId);
    
### Sending of Billing Information

For additional fraud checking, the merchant can send the customer’s billing address by using this function.

SOAP is required for this function.

    $transaction = new Transaction($client, 'SOAP');

Required parameters to be sent:

    $params = [
        'transactionId' => '12345',
        'firstName'     => 'John',
        'lastName'      => 'Doe',
        'address1'      => 'Address 1',
        'address2'      => 'Address 2',
        'city'          => 'Quezon City',
        'state'         => 'State',
        'country'       => 'Philippines',
        'zipCode'       => '1116',
        'telNo'         => '(02)9123456',
        'email'         => 'john@example.com'
    ];
    
Returns the status of sending of billing information. (string)

    $status = $transaction->sendBillingInformation($params);
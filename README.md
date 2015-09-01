Dragonpay Payment Switch API - PHP Library
========

A PHP Library for consuming Dragonpay's Payment Switch API.

## Quick start

### Required setup

The easiest way to install this library is via Composer.

Create a `composer.json` file and enter the following:
```json
{
    "require": {
        "coreproc/dragonpay-sdk": "dev-master"
    }
}
```

If you haven't yet downloaded your composer file, you can do so by executing the following in your command line:

    curl -sS https://getcomposer.org/installer | php

Once you've downloaded the composer.phar file, continue with your installation by running the following:

    php composer.phar install
    
## Usage (TODO: update)

### Setting up the Dragonpay Client

Require the autoloader and import the `DragonpayClient`:

```php
require '../vendor/autoload.php';

use Coreproc\Dragonpay\DragonpayClient;
```
    
Set the merchant credentials:

```php
$credentials = [
    'merchantId'        => 'merchant-id',
    'merchantPassword'  => 'merchant-password',
];
```
    
Logging is optional. It's set to false by default and can omitted when instantiating the client like so:

```php
$client = new DragonpayClient($credentials);
```
    
When set to true, a third parameter is required for the logging directory.

```php
$logging = true;
$logDirectory = 'logs';
```

Instantiate the client:

```php
$client = new DragonpayClient($credentials, $logging, $logDirectory);
```

### Checkout

Import the `Checkout` class:
```php
use Coreproc\Dragonpay\Checkout;
```

Instantiate the `Checkout` class like so:
```php
$checkout = new Checkout($client);
```
    
REST is the default web service used by the `Checkout` class. For using the SOAP web service, you can set the second parameter to `SOAP`:
```php
$checkout = new Checkout($client, 'SOAP');
```

Transaction parameters from the merchant site:

REST service specific required parameters:
```php    
$params = [
    'transactionId' => 'transaction-id',
    'amount'        => '20000.00',
    'currency'      => 'PHP',
    'description'   => 'Playstation 4',
    'email'         => 'john@example.com',
];
```

SOAP service specific required parameters:
```php
$params = [
    'transactionId' => 'transaction-id',
    'amount'        => '20000.99',
    'currency'      => 'PHP',
    'description'   => 'Playstation 4',
];
```

For getting the URL to Payment Switch:

The `getUrl` method returns a URL for redirecting to the Payment Switch manually.
```php
$url = $checkout->getUrl($params);
```
    
Now `$url` is equal to:

Using the REST web service:

    http://gw.dragonpay.ph/Pay.aspx?merchantid=merchant-id&txnid=transaction-id&amount=20000.00&ccy=PHP&description=Playstation+4&email=john%40example.com&digest=5ed24e0697800b569707542cff867eb2e9c681aa
    
Using the SOAP web service:

    http://gw.dragonpay.ph/Pay.aspx?tokenid=8ca5a73275d5f54cz06219a09f935c26

Use the `redirect` method for redirecting to the generated URL:
```php
$checkout->redirect($params);
```
    
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
```php
$filter = 'online_banking';
    
$checkout = new Checkout($client);
    
$checkout->getUrl($params, $filter);
    
$checkout->redirect($params, $filter);
```
    
### Handling the Response

Import the `Transaction` class:
```php
use Coreproc\Dragonpay\Transaction;
```

Pass in the client instance:
```php
$transaction = new Transaction($client);
```

Required request data from Dragonpay Payment Switch:
```php
$params = [
    'transactionId'   => $_GET['txnid'],
    'referenceNumber' => $_GET['refNo'],
    'status'          => $_GET['status'],
    'message'         => $_GET['message'],
    'digest'          => $_GET['digest']
];
```
    
Checking if the transaction is successful:

The `isSuccessful` method returns a boolean (true or false)
```php
$status = $transaction->isSuccessful($params); // returns true/false
```
    
### Support functions

The Payment Switch provides some supplementary functions allowing merchants to more tightly
integrate and automate their systems. These functions are available using the REST or SOAP web service.

### Transaction Inquiry

The merchant can inquire the status of a transaction by using this function.

Import the `Transaction` class:
```php
use Coreproc\Dragonpay\Transaction;
```
    
Instantiate the `Transaction` class like so:
```php
$transaction = new Transaction($client);
```
    
REST is the default web service used by the `Transaction` class. For using the SOAP web service, you can set the second parameter to `SOAP`:
```php
$transaction = new Transaction($client, 'SOAP');
```
    
The `inquire` method returns the status of the transaction (string)

```php
$transactionId = 'transaction-id';
    
// Returns one of the following:
// Success
// Failure
// Pending
// Unknown
// Refund
// Chargeback
// Void
// Authorized 
// Error
$status = $transaction->inquire($transactionId); 
```
    
### Cancellation of Transaction

The merchant can cancel a **pending** transaction by using this function.

The `cancel` method returns the status of the transaction cancellation (string)
```php
use Coreproc\Dragonpay\Transaction;
    
$transaction = new Transaction($client); // OR $transaction = new Transaction($client, 'SOAP');
    
$transactionId = 'transaction-id';
    
$status = $transaction->cancel($transactionId); // returns Success or Failed
```

### Sending of Billing Information

For additional fraud checking, the merchant can send the customer’s billing address by using this function.

The SOAP web service is required for this function.
```php
use Coreproc\Dragonpay\Transaction;
    
$transaction = new Transaction($client, 'SOAP');
```

Required parameters to be sent to Payment Switch:

```php
$params = [
    'transactionId' => '12345',
    'firstName'     => 'John',
    'lastName'      => 'Doe',
    'address1'      => 'Address 1',
    'address2'      => 'Address 2',
    'city'          => 'Quezon City',
    'state'         => 'State',
    'country'       => 'PH', // 2-char ISO country code (ex. PH, US, CA)
    'zipCode'       => '1116', // OPTIONAL
    'telNo'         => '(02)9123456',
    'email'         => 'john@example.com'
];
```
    
The `sendBillingInformation` method returns the status of sending of billing information. (string)

```php
$status = $transaction->sendBillingInformation($params); // returns Success or Failed
```

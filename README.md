# lightning-charge-client-php

PHP client for the Lightning Charge REST API.

## Install

```bash
$ composer require elementsproject/lightning-charge-client-php
```

## Use

```php
<?php
require_once 'vendor/autoload.php';

// Initialize client
$charge = new \LightningCharge\Client('http://localhost:8009', '[TOKEN]');

// Create invoice
$request = new \LightningCharge\InvoiceRequest();
$request->setMilliSatoshi(50);
$request->setMetadata(['customer' => [ 'customer' => 'Satoshi', 'products' => [ 'potato', 'chips']]]);
$invoice = $charge->invoice($request);

tell_user("to pay, send ".$invoice->getMilliSatoshi()." milli-satoshis with rhash ".$invoice->getRhash().", or copy the BOLT11 payment request: ".$invoice->getPayreq());


// Create invoice denominated in USD
$request = new \LightningCharge\InvoiceRequest();
$request->setCurrency('USD');
$request->setAmount(0.15);
$invoice = $charge->invoice($request);

// Fetch invoice by id
$invoice = $charge->fetch('m51vlVWuIKGumTLbJ1RPb');

// Fetch all invoices
$invoices = $charge->fetchAll();

// Register web hook
$charge->registerHook('m51vlVWuIKGumTLbJ1RPb', 'http://my-server.com/my-callback-url');
```

*TODO*: document `wait`

## Test

```bash
$ composer install
$ mkdir /tmp/data
$ docker run -u `id -u` -v /tmp/data:/data -p 9112:9112 \
             -e API_TOKEN=mySecretToken \
             shesek/lightning-charge
$ CHARGE_URL=http://api-token:mySecretToken@localhost:9112 vendor/bin/phpunit test
```

## License
MIT

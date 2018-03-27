<?php
require_once 'vendor/autoload.php';

use LightningCharge\Client;
use LightningCharge\InvoiceRequest;

class LightningChargeClientTest extends \PHPUnit\Framework\TestCase {

  public function test_create_invoice(){
    $charge = new Client(getenv('CHARGE_URL'));

    $request = new InvoiceRequest();
    $request->setMilliSatoshi(50);
    $request->setMetadata([ 'customer' => 'Satoshi', 'products' => [ 'potato', 'chips' ]]);

    $invoice = $charge->invoice($request);

    $this->assertNotEmpty($invoice->getId());
    $this->assertNotEmpty($invoice->getRhash());
    $this->assertNotEmpty($invoice->getPayreq());

    $this->assertEquals(50, $invoice->getMilliSatoshi());
    $this->assertEquals('Satoshi', $invoice->getMetadata()->customer);
    $this->assertEquals('chips', $invoice->getMetadata()->products[1]);
  }

  public function test_fetch_invoice(){
    $charge = new Client(getenv('CHARGE_URL'));

    $request = new InvoiceRequest();
    $request->setMilliSatoshi(50);
    $request->setMetadata('test_fetch_invoice');

    $saved = $charge->invoice($request);
    $loaded = $charge->fetch($saved->getId());

    $this->assertEquals($saved->getId(), $loaded->getId());
    $this->assertEquals($saved->getRhash(), $loaded->getRhash());
    $this->assertEquals($loaded->getMetadata(), 'test_fetch_invoice');
    $this->assertEquals($loaded->getMilliSatoshi(), 50);
  }

  public function test_register_webhook(){
    $charge = new Client(getenv('CHARGE_URL'));

    $request = new InvoiceRequest();
    $request->setMilliSatoshi(50);
    $invoice = $charge->invoice($request);

    $this->assertTrue($charge->registerHook($invoice->getId(), 'http://example.com/'));
  }
}

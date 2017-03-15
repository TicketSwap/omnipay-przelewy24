<?php

namespace Omnipay\Przelewy24;

use Omnipay\Przelewy24\Exception\NonValidChannelException;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @test
     */
    public function it_should_return_the_name()
    {
        $this->assertSame('Przelewy24', $this->gateway->getName());
    }

    /**
     * @test
     */
    public function it_should_return_default_parameters()
    {
        $defaultParameters = $this->gateway->getDefaultParameters();
        $this->assertSame('', $defaultParameters['merchantId']);
        $this->assertSame('', $defaultParameters['posId']);
        $this->assertSame('', $defaultParameters['crc']);
        $this->assertFalse($defaultParameters['testMode']);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_merchant_id()
    {
        $merchantId = 42;

        $this->gateway->setMerchantId($merchantId);
        $this->assertSame($merchantId, $this->gateway->getMerchantId());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_pos_id()
    {
        $posId = 13;

        $this->gateway->setPosId($posId);
        $this->assertSame($posId, $this->gateway->getPosId());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_crc()
    {
        $crc = '1288348798';

        $this->gateway->setCrc($crc);
        $this->assertSame($crc, $this->gateway->getCrc());
    }

    /**
     * @test
     */
    public function it_should_set_and_get_channel()
    {
        $channel = 32;

        $this->gateway->setChannel($channel);
        $this->assertSame($channel, $this->gateway->getChannel());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_setting_an_unsupported_channel()
    {
        $this->setExpectedException(NonValidChannelException::class);
        $this->gateway->setChannel(100);
    }

    /**
     * @test
     */
    public function it_should_create_a_purchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Przelewy24\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    /**
     * @test
     */
    public function it_should_complete_a_purchase()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Przelewy24\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}

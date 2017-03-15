<?php

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Przelewy24\Gateway;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $card = new CreditCard(array(
            'email' => 'test@example.com',
            'country' => 'NL',
        ));

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantId'  => '144354',
            'posId'       => '144354',
            'crc'         => '1287875353948',
            'sessionId'   => '42',
            'amount'      => '12.00',
            'currency'    => 'PLN',
            'description' => 'Description',
            'returnUrl'   => 'https://www.example.com/return',
            'notifyUrl'   => 'https://www.example.com/notify',
            'card'        => $card,
        ));
    }

    public function channelProvider() {
        return [
            [Gateway::P24_CHANNEL_USE_PREPAYMENT],
            [null],
        ];
    }
    /**
     * @dataProvider channelProvider
     */
    public function testGetData($channel)
    {
        $card = new CreditCard(array(
            'email' => 'test@example.com',
            'country' => 'NL',
        ));

        $this->request->initialize(array(
            'merchantId'  => '144354',
            'posId'       => '144354',
            'crc'         => '1287875353948',
            'sessionId'   => '42',
            'amount'      => '12.00',
            'currency'    => 'PLN',
            'description' => 'Description',
            'returnUrl'   => 'https://www.example.com/return',
            'notifyUrl'   => 'https://www.example.com/notify',
            'card'        => $card,
            'channel'     => $channel,
        ));

        $data = $this->request->getData();

        $this->assertSame("42", $data['p24_session_id']);
        $this->assertSame(1200, $data['p24_amount']);
        $this->assertSame("PLN", $data['p24_currency']);
        $this->assertSame('Description', $data['p24_description']);
        $this->assertSame("test@example.com", $data['p24_email']);
        $this->assertSame("", $data['p24_client']);
        $this->assertSame("NL", $data['p24_country']);
        $this->assertSame('https://www.example.com/return', $data['p24_url_return']);
        $this->assertSame('https://www.example.com/notify', $data['p24_url_status']);
        $this->assertSame('d565d579d28f4374a7c2852a8e3f8fd7', $data['p24_sign']);
        $this->assertSame('3.2', $data['p24_api_version']);

        if (null === $channel) {
            $this->assertCount(15, $data);
        } else {
            $this->assertSame($channel, $data['p24_channel']);
            $this->assertCount(16, $data);
        }

    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Przelewy24\Message\PurchaseResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertEquals(
            'https://secure.przelewy24.pl/trnRequest/3F17389551-5285CA-F0B10D-A700D9B023',
            $response->getRedirectUrl()
        );
        $this->assertNull($response->getRedirectData());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('0', $response->getCode());
        $this->assertNull($response->getMessage());
    }

    public function testSendSignatureFailure()
    {
        $this->setMockHttpResponse('PurchaseSignatureFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Przelewy24\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('err00', $response->getCode());
        $this->assertSame('Invalid CRC', $response->getMessage());
    }
}

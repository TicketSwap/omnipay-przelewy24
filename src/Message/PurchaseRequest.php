<?php

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * @todo: Reason this class exists
 */
class PurchaseRequest extends AbstractRequest
{
    private static $apiVersion = '3.2';

    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getItems()
    {
        return $this->getParameter('items');
    }

    public function setItems($value)
    {
        return $this->setParameter('items', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('sessionId', 'amount', 'currency', 'description', 'card', 'returnUrl', 'notifyUrl');

        $data = array(
            'p24_session_id' => $this->getSessionId(),
            'p24_amount' => $this->getAmountInteger(),
            'p24_currency' => $this->getCurrency(),
            'p24_description' => $this->getDescription(),
            'p24_email' => $this->getCard()->getEmail(),
            'p24_client' => $this->getCard()->getName(),
            'p24_address' => $this->getCard()->getAddress1(),
            'p24_zip' => $this->getCard()->getPostcode(),
            'p24_city' => $this->getCard()->getCity(),
            'p24_country' => $this->getCard()->getCountry(),
            'p24_phone' => $this->getCard()->getPhone(),
            'p24_url_return' => $this->getReturnUrl(),
            'p24_url_status' => $this->getNotifyUrl(),
            'p24_sign' => $this->generateSignature(
                $this->getSessionId(),
                $this->getPosId(),
                $this->getAmountInteger(),
                $this->getCurrency(),
                $this->getCrc()
            ),
            'p24_api_version' => self::$apiVersion,
        );

        if (null !== $this->getChannel()) {
            $data['p24_channel'] = $this->getChannel();
        }

        $items = $this->getItems();
        if ($items) {
            $index = 1;
            foreach ($items as $item) {
                $data['pr24_name_' . $index] = $item['name'];
                $data['pr24_quantity_' . $index] = $item['quantity'];
                $data['pr24_price_' . $index] = $item['price'];
                $data['pr24_description_' . $index] = $item['description'];

                $index++;
            }
        }

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('POST', 'trnRegister', $data);

        $responseData = array();
        parse_str($httpResponse->getBody(), $responseData);

        return $this->response = new PurchaseResponse($this, $responseData, $this->getEndpoint());
    }

    /**
     * @param $sessionId
     * @param $posId
     * @param $amount
     * @param $currency
     * @param $crc
     * @return string
     */
    private function generateSignature($sessionId, $posId, $amount, $currency, $crc)
    {
        return md5(sprintf('%s|%s|%s|%s|%s', $sessionId, $posId, $amount, $currency, $crc));
    }
}

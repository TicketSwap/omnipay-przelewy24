<?php

namespace Omnipay\Przelewy24;

use Omnipay\Common\AbstractGateway;
use Omnipay\Przelewy24\Exception\NonValidChannelException;

/**
 * Przelewy24 Gateway
 */
class Gateway extends AbstractGateway
{
    const P24_CHANNEL_CC = 1;
    const P24_CHANNEL_BANK_TRANSFERS = 2;
    const P24_CHANNEL_MANUAL_TRANSFER = 4;
    const P24_CHANNEL_ALL_METHODS_24_7 = 16;
    const P24_CHANNEL_USE_PREPAYMENT = 32;
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Przelewy24';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'posId'      => '',
            'crc'        => '',
            'testMode'   => false,
        );
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getPosId()
    {
        return $this->getParameter('posId');
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setPosId($value)
    {
        return $this->setParameter('posId', $value);
    }

    /**
     * @return string
     */
    public function getCrc()
    {
        return $this->getParameter('crc');
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setCrc($value)
    {
        return $this->setParameter('crc', $value);
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->getParameter('channel');
    }

    /**
     * @param  int $value
     * @return $this
     * @throws NonValidChannelException
     */
    public function setChannel($value)
    {
        $this->validateChannelValue($value);

        return $this->setParameter('channel', $value);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Przelewy24\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Przelewy24\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     * @return \Omnipay\Przelewy24\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Przelewy24\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @return array
     */
    private function getValidChannelValues()
    {
        $validChannelValues = [
            self::P24_CHANNEL_CC,
            self::P24_CHANNEL_BANK_TRANSFERS,
            self::P24_CHANNEL_MANUAL_TRANSFER,
            self::P24_CHANNEL_ALL_METHODS_24_7,
            self::P24_CHANNEL_USE_PREPAYMENT,
        ];

        return $validChannelValues;
    }

    /**
     * @param $value
     *
     * @throws NonValidChannelException
     */
    private function validateChannelValue($value)
    {
        if (!in_array($value, $this->getValidChannelValues())) {
            throw new NonValidChannelException();
        }
    }
}

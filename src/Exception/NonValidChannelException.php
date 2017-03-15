<?php

namespace Omnipay\Przelewy24\Exception;

class NonValidChannelException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The provided channel is not valid');
    }
}

# Omnipay: Przelewy24

**Przelewy24 gateway for the Omnipay PHP payment processing library**

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/TicketSwap/omnipay-przelewy24.svg?branch=master)](https://travis-ci.org/TicketSwap/omnipay-przelewy24)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Przelewy24 support for Omnipay.

For more information about the Przelewy24 API, take a look at the [manual](http://www.przelewy24.pl/files/cms/13/przelewy24_specification.pdf)

## Install

This gateway can be installed with [Composer](https://getcomposer.org/):

``` bash
$ composer require ticketswap/omnipay-przelewy24
```

## Usage

The following gateways are provided by this package:

 * Przelewy24

## Example

```php

require_once  __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;

/** @var \Omnipay\Przelewy24\Gateway $gateway */
$gateway = Omnipay::create('Przelewy24');

$gateway->initialize([
    'merchantId' => 'YOUR MERCHANT ID HERE',
    'posId'      => 'YOUR POS ID HERE',
    'crc'        => 'YOUR CRC KEY HERE',
    'testMode'   => true,
]);

$params = array(
    'sessionId' => 2327398739,
    'amount' => 12.34,
    'currency' => 'PLN',
    'description' => 'Payment test',
    'returnUrl' => 'www.your-domain.nl/return_here',
    'notifyUrl' => 'www.your-domain.nl/notify_here',
    'card' => array(
        'email' => 'info@example.com',
        'name' => 'My name',
        'country' => 'NL',
    ),
);

$response = $gateway->purchase($params)->send();

if ($response->isSuccessful()) {
    $response->redirect();
} else {
    echo 'Failed';
}
```

Optionally you can specify the payment channels allowed adding the 'channel' parameter in the Gateway
initialization call.

```
$gateway->initialize([
    //[...]
    'channel' => Gateway::P24_CHANNEL_CC,
]);
    
```

For a list of all the supported values for 'Channel' you can read the [przelewy24 documentation](http://www.przelewy24.pl/eng/storage/app/media/pobierz/Instalacja/przelewy24_specification.pdf)

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/ticketswap/omnipay-przelewy24/issues),
or better yet, fork the library and submit a pull request.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email info@ticketswap.com instead of using the issue tracker.

## Credits

- [TicketSwap](https://github.com/ticketswap)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

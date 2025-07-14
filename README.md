# KudiSMS PHP

[![Test & Lint](https://github.com/toneflix/kudi-sms-php/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/toneflix/kudi-sms-php/actions/workflows/php.yml)
[![Latest Stable Version](http://poser.pugx.org/toneflix-code/kudi-sms-php/v)](https://packagist.org/packages/toneflix-code/kudi-sms-php) [![Total Downloads](http://poser.pugx.org/toneflix-code/kudi-sms-php/downloads)](https://packagist.org/packages/toneflix-code/kudi-sms-php) [![Latest Unstable Version](http://poser.pugx.org/toneflix-code/kudi-sms-php/v/unstable)](https://packagist.org/packages/toneflix-code/kudi-sms-php) [![License](http://poser.pugx.org/toneflix-code/kudi-sms-php/license)](https://packagist.org/packages/toneflix-code/kudi-sms-php) [![PHP Version Require](http://poser.pugx.org/toneflix-code/kudi-sms-php/require/php)](https://packagist.org/packages/toneflix-code/kudi-sms-php)
[![codecov](https://codecov.io/gh/toneflix/kudi-sms-php/graph/badge.svg?token=2O7aFulQ9P)](https://codecov.io/gh/toneflix/kudi-sms-php)

[KudiSMS Documentation](https://developer.kudisms.net/)

KudiSMS PHP is a PHP wrapper library for KudiSMS.

## Quick Start

### Installation

```bash
composer require toneflix-code/kudi-sms-php
```

### Configure environment

| Variable             | Required | Description                       |
| -------------------- | -------- | --------------------------------- |
| KUDISMS_GATEWAY      | No       | Your prefered gateway             |
| KUDISMS_API_KEY      | Yes      | Your API key                      |
| KUDISMS_SENDER_ID    | Yes      | SMS Sender ID                     |
| KUDISMS_TEST_NUMBERS | No       | Numbers to use when running tests |

## Usage

### Initialize

To initialize, simply call create new instance of the `SmsSender` class.

```php
use ToneflixCode\KudiSmsPhp\SmsSender;

$instance = new SmsSender();
```

Optionally you can pass your `sender ID` and `API key` as parameters to the instance if you're unable to use environment variables.

```php
use ToneflixCode\KudiSmsPhp\SmsSender;

$instance = new SmsSender('SenderID', 'APIKey');
```

### Send OTP

Call the `sendOtp` method with the recipient, otp, appnamecode and templatecode as parameters.

```php
$instance->sendOtp(
    recipient: '0807...',
    otp: string,
    appnamecode: string,
    templatecode: string
);
```

### Send to a single number

Call the `send` method with the number and message as parameters.

```php
$instance->send(
    recipient: '0807...',
    message: string
);
```

### Send to a multiple numbers

Call the `sendBulk` method with an array of numbers and message as parameters.

```php
$instance->sendBulk(
    recipients: ['0807...', '0903...'],
    message: string
);
```

### Corporate SMS

To send using the corporate endpoint call the chainable `corporate` method before calling the `send` or `sendBulk` methods.

```php
$instance->corporate()->send(
    recipient: '0807...',
    message: string
);
```

### Initialize Voice Messaging

To initialize, simply call create new instance of the `VoiceSender` class.

```php
use ToneflixCode\KudiSmsPhp\VoiceSender;

$instance = new VoiceSender();
```

Optionally you can pass your `Caller ID` and `API key` as parameters to the instance if you're unable to use environment variables.

```php
use ToneflixCode\KudiSmsPhp\SmsSender;

$instance = new SmsSender('CallerId', 'APIKey');
```

### Send voice message

Call the `send` method with the number and a valid audio file url as parameters.

```php
$instance->send(
    to: '0807...',
    url: string
);
```

### Send text to speach message

Call the `tts` method with the number and message as parameters.

```php
$instance->tts(
    to: '0807...',
    message: string
);
```

## Testing

```bash
$ composer test
```

All tests are available withing the `tests` directory, most are skipped as the service is not free, you can also write your own tests.

## Contributors

- [Legacy](https://github.com/3m1n3nc3)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

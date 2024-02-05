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

| Variable            | Required | Description              |
|---------------------|----------|--------------------------|
| KUDISMS_GATEWAY     | No       | Your prefered gateway    |
| KUDISMS_API_KEY     | Yes      | Your API key             |
| KUDISMS_SENDER_ID   | Yes      | SMS Sender ID            |

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

### Send to a single number

Call the `send` method with the number and message as parameters.

```php
$instance->send('0807...', 'This is a test SMS.');
```

### Send to a multiple numbers

Call the `send` method with an array of numbers and message as parameters.

```php
$instance->send(['0807...', '0903...'], 'This is a test SMS.');
```

## Contributors

- [Legacy](https://github.com/3m1n3nc3)

## License
[MIT](./LICENSE)
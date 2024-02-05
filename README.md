# KudiSMS PHP

[![Test & Lint](https://github.com/toneflix/kudi-sms-php/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/toneflix/kudi-sms-php/actions/workflows/php.yml)
[![Latest Stable Version](https://poser.pugx.org/toneflix/kudi-sms-php/v/stable.png)](https://packagist.org/packages/toneflix-code/kudi-sms-php)
[![Total Downloads](https://poser.pugx.org/toneflix/kudi-sms-php/downloads.png)](https://packagist.org/packages/toneflix-code/kudi-sms-php)
[![codecov](https://codecov.io/gh/toneflix/kudi-sms-php/graph/badge.svg?token=2O7aFulQ9P)](https://codecov.io/gh/toneflix/kudi-sms-php)

[KudiSMS Documentation](https://developer.kudisms.net/)

KudiSMS PHP is a PHP wrapper library for KudiSMS.

## Quick Start

### Installation

```bash
composer require toneflix/kudi-sms-php
```

### Configure environment

| Variable            | Required | Description              |
|---------------------|----------|--------------------------|
| KUDISMS_GATEWAY     | No       | Your prefered gateway    |
| KUDISMS_API_KEY     | Yes      | Your API key             |
| KUDISMS_SENDER_ID   | Yes      | SMS Sender ID            |
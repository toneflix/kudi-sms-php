<?php

namespace ToneflixCode\KudiSmsPhp\Tests;

use ToneflixCode\KudiSmsPhp\SmsSender;
use ToneflixCode\MessagingInterface\Exceptions\SmsSendingException;

test('Can Send Sms To Single Recipient', function () {
    loadEnv();
    $numbers = explode(',', $_ENV['KUDISMS_TEST_NUMBERS'] ?? $_SERVER['KUDISMS_TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        $sent = (new SmsSender())->send($numbers[0], 'This is a test SMS.');
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Can Send Sms To Multiple Recipients', function () {
    loadEnv();
    $numbers = explode(',', $_ENV['KUDISMS_TEST_NUMBERS'] ?? $_SERVER['KUDISMS_TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        $sent = (new SmsSender())->sendBulk(
            $numbers,
            'This is a test SMS to multiple recipients.'
        );
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Throws Invalid SenderId', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['KUDISMS_TEST_NUMBERS'] ?? $_SERVER['KUDISMS_TEST_NUMBERS'] ?? '');
    $otp = rand(111111, 999999);
    if (isset($numbers[0])) {
        expect(fn() => (new SmsSender('xxx'))->send($numbers[0], $otp, 'TonEfLiX', '1111'))
            ->toThrow(SmsSendingException::class, 'Unable to send sms: The sender ID used does not exist.');
    } else {
        expect(false)->toBeTrue();
    }
});

<?php

namespace ToneflixCode\Tests;

use ToneflixCode\KudiSmsPhp\SmsSender;
use ToneflixCode\MessagingInterface\Exceptions\SmsSendingException;

test('Can Send Otp To Recipient', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');
    $otp = rand(111111, 999999);

    if (isset($numbers[0])) {
        try {
            $sent = (new SmsSender())->sendOtp($numbers[0], $otp, 'TonEfLiX', '1111');
        } catch (\Throwable $th) {
            $sent = false;
        }
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Throws Invalid SenderId', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');
    $otp = rand(111111, 999999);

    if (isset($numbers[0])) {
        expect(fn () => (new SmsSender('xxx'))->sendOtp($numbers[0], $otp, 'TonEfLiX', '1111'))
        ->toThrow(SmsSendingException::class, 'Unable to send sms: The sender ID do not exist.');
    } else {
        expect(false)->toBeTrue();
    }
});

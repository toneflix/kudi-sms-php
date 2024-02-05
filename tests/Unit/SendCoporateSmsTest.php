<?php

namespace ToneflixCode\Tests;

use ToneflixCode\KudiSmsPhp\SmsSender;
use ToneflixCode\MessagingInterface\Exceptions\SmsSendingException;

test('Can Send Sms To Single Recipient', function () {
    loadEnv();
    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        try {
            $sent = (new SmsSender())->corporate()->send($numbers[0], 'This is a test SMS.');
        } catch (\Throwable $th) {
            $sent = false;
        }
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Can Send Sms To Multiple Recipients', function () {
    loadEnv();
    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        try {
            $sent = (new SmsSender())->corporate()->sendBulk(
                $numbers,
                'This is a test SMS to multiple recipients.'
            );
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

    if (isset($numbers[0])) {
        expect(fn () => (new SmsSender('xxx'))->corporate()->send($numbers[0], 'This is a test SMS.'))
        ->toThrow(SmsSendingException::class, 'Unable to send sms: The sender ID do not exist.');
    } else {
        expect(false)->toBeTrue();
    }
});

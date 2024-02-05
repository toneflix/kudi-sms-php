<?php

namespace ToneflixCode\KudiSmsPhp\Tests;

use ToneflixCode\KudiSmsPhp\VoiceSender;
use ToneflixCode\MessagingInterface\Exceptions\VoiceSendingException;

test('Can Send Voice Message', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        try {
            $sent = (new VoiceSender())->send($numbers[0], 'https://download.samplelib.com/mp3/sample-3s.mp3');
        } catch (\Throwable $th) {
            $sent = false;
        }
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Can Send Text To Speach Message', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        try {
            $sent = (new VoiceSender())->tts($numbers[0], 'Hello John Doe');
        } catch (\Throwable $th) {
            $sent = false;
        }
        expect($sent)->toBeTrue();
    } else {
        expect(false)->toBeTrue();
    }
})->skip('Skipped for cost saving.');

test('Throws Invalid Caller ID', function () {
    loadEnv();

    $numbers = explode(',', $_ENV['TEST_NUMBERS'] ?? $_SERVER['TEST_NUMBERS'] ?? '');

    if (isset($numbers[0])) {
        expect(fn () => (new VoiceSender())->send($numbers[0], 'https://download.samplelib.com/mp3/sample-3s.mp3'))
        ->toThrow(VoiceSendingException::class, 'Unable to send voice message: The caller ID used do not exist.');
    } else {
        expect(false)->toBeTrue();
    }
});

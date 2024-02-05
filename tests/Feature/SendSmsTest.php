<?php

namespace ToneflixCode\Tests;

use ToneflixCode\KudiSmsPhp\SmsSender;

test('canSendSmsToSingleRecipient', function () {
    $sent = (new SmsSender())->send('08075654709', 'This is a test SMS.');
    expect($sent)->toBeTrue();
})->skip('Skipped for cost saving.');

test('canSendSmsToMultipleRecipients', function () {
    $sent = (new SmsSender())->sendBulk(
        ['08075654709', '+2349098544991'],
        'This is a test SMS to multiple recipients.'
    );
    expect($sent)->toBeTrue();
})->skip('Skipped for cost saving.');
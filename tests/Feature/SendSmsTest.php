<?php

namespace ToneflixCode\Tests;

use ToneflixCode\KudiSmsPhp\SendSms;

test('canSendSmsToSingleRecipient', function () {
    $sent = (new SendSms())->send('08075654709', 'This is a test SMS.');
    expect($sent)->toBeTrue();
});

test('canSendSmsToMultipleRecipients', function () {
    $sent = (new SendSms())->sendBulk(
        ['08075654709', '+2349098544991'],
        'This is a test SMS to multiple recipients.'
    );
    expect($sent)->toBeTrue();
});
<?php

namespace  ToneflixCode\KudiSmsPhp;

use ToneflixCode\MessagingInterface\Exceptions\InitializationException;
use ToneflixCode\MessagingInterface\Exceptions\SmsSendingException;
use ToneflixCode\MessagingInterface\Initializable;
use ToneflixCode\MessagingInterface\SmsInterface;
use ToneflixCode\MessagingInterface\OtpInterface;

class SmsSender implements SmsInterface, OtpInterface
{
    use Initializable;

    public string $baseUrl;
    public \GuzzleHttp\Client $client;

    public function __construct(string $senderId = null, string $apiKey = null)
    {
        // Set the base url
        $this->baseUrl = 'https://my.kudisms.net/api/';

        // Load the .env file
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ .  '/..');
        $dotenv->safeLoad();

        // Initialize Guzzle
        $this->client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

        // Load the configuration
        $this->configure(
            $senderId ?? $_ENV['KUDISMS_SENDER_ID'] ?? $_SERVER['KUDISMS_SENDER_ID'] ?? null,
            $apiKey ?? $_ENV['KUDISMS_API_KEY'] ?? $_SERVER['KUDISMS_API_KEY'] ?? null,
            $gateway ?? $_ENV['KUDISMS_GATEWAY'] ?? $_SERVER['KUDISMS_GATEWAY'] ?? null,
        );
    }

    /**
     *  Send an SMS to a single recipient.
     *
     * @param string $to
     * @param  string $message
     *
     * @throws \ToneflixCode\MessagingInterface\Exceptions\SmsSendingException
     * @return bool
     *
    */
    public function send(string $recipient, string $message): bool
    {
        return $this->sendBulk([$recipient], $message);
    }


    /**
     *  Send an SMS to multiple recipients
     *
     * @param array $to
     * @param  string $message
     *
     * @throws SmsSendingException
     * @return bool
     *
    */
    public function sendBulk(array $recipients, string $message): bool
    {
        $recipients = join(',', $recipients);

        // Try to send the SMS
        try {
            $response = $this->client->post($this->endpoint ?? 'sms', [
                'query' => [
                    'token' => $this->apiKey,
                    'senderID' => $this->senderId,
                    'gateway' => $this->gateway,
                    'message' => $message,
                    'recipient' => $recipients,
                ],
                'multipart' => $this->params($recipients, $message)
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $th) {
            // If the error thrown is a 401 throw the configuration error.
            $error = json_decode($th->getResponse()->getBody(), JSON_FORCE_OBJECT);

            if ($th->getResponse()->getStatusCode() === 401) {
                throw new InitializationException($error['msg'] ?? $error['message'] ?? '', 1);
            }

            //  Re-throw any other exception as an APIException
            throw new SmsSendingException($error['msg'] ?? $error['message'] ?? '', 1);
        }

        // Parse the returned response
        $data = json_decode($response->getBody(), JSON_FORCE_OBJECT);

        if (isset($data['status']) && $data['status'] === 'error') {
            if (in_array($data['error_code'], [100, 101, 103, 105])) {
                throw new InitializationException($data['msg'], 1);
            } else {
                throw new SmsSendingException($data['msg'] ?? '', 1);
            }
        }

        if (isset($data['status']) && $data['status'] === 'success') {
            return $data['error_code'] == 000;
        }

        return false;
    }

    /**
     *  Send an otp to a number.
     *
     * @param string $to
     * @param  string $otp
     * @param  string $appnamecode
     * @param  string $templatecode
     *
     * @throws \ToneflixCode\MessagingInterface\Exceptions\SmsSendingException
     * @return bool
     *
    */
    public function sendOtp(string $recipient, string $otp, string $appnamecode, string $templatecode): bool
    {
        // Try to send the SMS
        try {
            $response = $this->client->post('otp', [
                'multipart' => $this->params($recipient, null, [
                    [
                        'name' => 'otp',
                        'contents' => $otp,
                    ],
                    [
                        'name' => 'appnamecode',
                        'contents' => $appnamecode,
                    ],
                    [
                        'name' => 'templatecode',
                        'contents' => $templatecode,
                    ],
                ])
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $th) {
            // If the error thrown is a 401 throw the configuration error.
            $error = json_decode($th->getResponse()->getBody(), JSON_FORCE_OBJECT);

            if ($th->getResponse()->getStatusCode() === 401) {
                throw new InitializationException($error['msg'] ?? $error['message'] ?? '', 1);
            }

            //  Re-throw any other exception as an APIException
            throw new SmsSendingException($error['msg'] ?? $error['message'] ?? '', 1);
        }

        // Parse the returned response
        $data = json_decode($response->getBody(), JSON_FORCE_OBJECT);

        if (isset($data['status']) && $data['status'] === 'error') {
            if (in_array($data['error_code'], [100, 101, 103, 105])) {
                throw new InitializationException($data['msg'], 1);
            } else {
                throw new SmsSendingException($data['msg'] ?? $data['message'] ?? '', 1);
            }
        }

        if (isset($data['status']) && $data['status'] === 'success') {
            return $data['error_code'] == 000;
        }

        return false;
    }
}

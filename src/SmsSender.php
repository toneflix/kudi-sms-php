<?php

namespace  ToneflixCode\KudiSmsPhp;

use ToneflixCode\SmsInterface\Exceptions\InitializationException;
use ToneflixCode\SmsInterface\Exceptions\SmsSendingException;
use ToneflixCode\SmsInterface\Initializable;
use ToneflixCode\SmsInterface\SendSmsInterface;

class SmsSender implements SendSmsInterface
{
    use Initializable;

    public \GuzzleHttp\Client $client;
    public string $baseUrl = 'https://my.kudisms.net/api/';

    public function __construct(string $senderID = null, string $apiKey = null)
    {
        // Load the .env file
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ .  '/..');
        $dotenv->safeLoad();

        // Initialize Guzzle
        $this->client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

        // Load the configuration
        $this->configure(
            $senderID ?? $_ENV['KUDISMS_SENDER_ID'] ?? $_SERVER['KUDISMS_SENDER_ID'] ?? null,
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
     * @throws \ToneflixCode\SmsInterface\Exceptions\SmsSendingException
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
            $response = $this->client->post('sms', [
                'query' => [
                    'token' => $this->apiKey,
                    'senderID' => $this->senderID,
                    'gateway' => $this->gateway,
                    'message' => $message,
                    'recipient' => $recipients,
                ],
                'multipart' => [
                    [
                        'name' => 'token',
                        'contents' => $this->apiKey,
                    ],
                    [
                        'name' => 'senderID',
                        'contents' => $this->senderID,
                    ],
                    [
                        'name' => 'recipients',
                        'contents' => $recipients
                    ],
                    [
                        'name' => 'message',
                        'contents' => $message
                    ],
                    [
                        'name' => 'gateway',
                        'contents' => $this->gateway,
                    ]
                ]
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
            throw new InitializationException($data['msg'], 1);
        }

        if (isset($data['status']) && $data['status'] === 'success') {
            return $data['error_code'] == 000;
        }

        return false;
    }
}

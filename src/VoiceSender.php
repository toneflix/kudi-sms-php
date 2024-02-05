<?php

namespace  ToneflixCode\KudiSmsPhp;

use ToneflixCode\MessagingInterface\Exceptions\InitializationException;
use ToneflixCode\MessagingInterface\Exceptions\VoiceSendingException;
use ToneflixCode\MessagingInterface\Initializable;
use ToneflixCode\MessagingInterface\VoiceInterface;

class VoiceSender implements VoiceInterface
{
    use Initializable;

    public string $baseUrl;
    public \GuzzleHttp\Client $client;

    public function __construct(string $callerID = null, string $apiKey = null)
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
            $callerID ?? $_ENV['KUDISMS_SENDER_ID'] ?? $_SERVER['KUDISMS_SENDER_ID'] ?? null,
            $apiKey ?? $_ENV['KUDISMS_API_KEY'] ?? $_SERVER['KUDISMS_API_KEY'] ?? null,
            $gateway ?? $_ENV['KUDISMS_GATEWAY'] ?? $_SERVER['KUDISMS_GATEWAY'] ?? null,
        );
    }

    /**
     *  Send an SMS to a single recipient.
     *
     * @param string $to
     * @param  string $url
     *
     * @throws \ToneflixCode\MessagingInterface\Exceptions\VoiceSendingException
     * @return bool
     *
    */
    public function send(string $to, string $url): bool
    {
        return $this->sendMessage(
            $this->params($to, null, [
                [
                    'name' => 'audio',
                    'contents' => $url
                ]
            ], true)
        );
    }

    /**
     *  Send an SMS to a single recipient.
     *
     * @param string $to
     * @param  string $message
     *
     * @throws \ToneflixCode\MessagingInterface\Exceptions\VoiceSendingException
     * @return bool
     *
    */
    public function tts(string $to, string $message): bool
    {
        $this->endpoint = 'texttospeech';

        return $this->sendMessage(
            $this->params($to, $message, [], true)
        );
    }


    /**
     *  Send an SMS to multiple recipients
     *
     * @param array $to
     * @param  array $params
     *
     * @throws VoiceSendingException
     * @return bool
     *
    */
    public function sendMessage(array $params = []): bool
    {
        // Try to send the voice
        try {
            $response = $this->client->post($this->endpoint ?? 'voice', [
                'multipart' => $params
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $th) {
            // If the error thrown is a 401 throw the configuration error.
            $error = json_decode($th->getResponse()->getBody(), JSON_FORCE_OBJECT);

            if ($th->getResponse()->getStatusCode() === 401) {
                throw new InitializationException($error['msg'] ?? $error['message'] ?? '', 1);
            }

            //  Re-throw any other exception as an APIException
            throw new VoiceSendingException($error['msg'] ?? $error['message'] ?? '', 1);
        }

        // Parse the returned response
        $data = json_decode($response->getBody(), JSON_FORCE_OBJECT);

        if (isset($data['status']) && $data['status'] === 'error') {
            if (in_array($data['error_code'], [100, 101, 103, 105])) {
                throw new InitializationException($data['msg'], 1);
            } else {
                throw new VoiceSendingException($data['msg'] ?? '', 1);
            }
        }

        if (isset($data['status']) && $data['status'] === 'success') {
            return $data['error_code'] == 000;
        }

        return false;
    }
}

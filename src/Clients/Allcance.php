<?php

namespace Bnw\SmsManager\Clients;

use Exception;
use Bnw\SmsManager\SmsMessage;
use Bnw\SmsManager\SmsResponse;
use Bnw\SmsManager\Contracts\Sms as SmsContract;
use Bnw\SmsManager\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client as HttpClient;

class Allcance implements SmsContract
{
    /**
     * Allance API base URL.
     *
     * @var string
     */
    protected $baseUrl = 'http://api.allcancesms.com.br/';

    /**
     * Allance API authentication.
     *
     * @var string
     */
    protected $auth;

    /**
    * @param \GuzzleHttp\Client $http
    * @param string $token
    */
    public function __construct($auth)
    {
        $this->httpClient = new HttpClient(['base_uri' => $this->baseUrl]);

        $this->auth = $auth;
    }

    public function sendMessages(array $phones, SmsMessage $message) : array
    {
        $responses = [];

        foreach($phones as $phone) {

            try {
                $responses[] = $this->sendMessage($phone, $message);
            } catch (Exception $exception) {
                if(count($responses) === 0) {
                    throw new Exception($exception->getMessage(), $exception->getCode());
                }
            }

        }

        return $responses;
    }

    public function sendMessage(String $phone, SmsMessage $message) : SmsResponse
    {
        try {
            $response = $this->httpClient->request('POST', 'sms/1/text/single', [
                'headers' => $this->getHeaders(),
                'json' => [
                    'from' => 'Consaude',
                    'to' => $phone,
                    'text' => $message->message
                ],
            ]);
        } catch (RequestException $exception) {
            if ($response = $exception->getResponse()) {
                throw CouldNotSendNotification::serviceRespondedWithAnHttpError($response);
            }

            throw CouldNotSendNotification::serviceCommunicationError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceCommunicationError($exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnHttpError($response);
        }

        $response = json_decode($response->getBody(), true);

        if ($response === null) {
            throw new Exception("SMS responded with response null");
        }

        if (!array_key_exists('messages', $response)) {
            throw new Exception("SMS responded without value 'messages' in the json");
        }

        $retMessage = $response['messages'][0];

        if ($retMessage['status']['groupName'] === 'REJECTED') {
            throw new Exception("SMS responded with status REJECTED, cause: " . $retMessage['status']['description']);
        }

        return (new SmsResponse)
            ->messageId($retMessage['messageId'])
            ->message($message->message)
            ->status('sent')
            ->phone($phone);
    }

    public function getHeaders() : array
    {
        return [
            'Authorization' => 'Basic ' . $this->auth
        ];
    }
}

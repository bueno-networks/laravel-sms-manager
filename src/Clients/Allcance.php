<?php

namespace Bnw\SmsManager\Clients;

use Bnw\SmsManager\SmsMessage;
use Bnw\SmsManager\Contracts\Sms as SmsContract;
use Bnw\SmsManager\Exceptions\CouldNotSendNotification;
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

    public function sendMessages(array $phones, SmsMessage $message)
    {
        foreach($phones as $phone) {
            $this->sendMessage($phone, $message);
        }
    }

    public function sendMessage(String $phone, SmsMessage $message)
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

        $body = json_decode($response->getBody(), true);

        dd($body);
    }

    public function getHeaders() : array
    {
        return [
            'Authorization' => 'Basic ' . $this->auth
        ];
    }
}

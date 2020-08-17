<?php

namespace bnw\SmsManager\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class CouldNotSendNotification extends Exception
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return static
     */
    public static function serviceRespondedWithAnHttpError(ResponseInterface $response)
    {
        $message = "SMS responded with an HTTP error: {$response->getStatusCode()}";

        $message .= ":". json_decode($response->getBody());

        return new static($message);
    }

    /**
     * @param array $response
     *
     * @return static
     */
    public static function serviceRespondedWithAnApiError(array $response)
    {
        return new static("SMS responded with an API error: {$response['code']}: {$response['message']}");
    }

    /**
     * @param \Exception $exception
     *
     * @return static
     */
    public static function serviceCommunicationError(Exception $exception)
    {
        return new static("Communication with SMS failed: {$exception->getCode()}: {$exception->getMessage()}");
    }
}

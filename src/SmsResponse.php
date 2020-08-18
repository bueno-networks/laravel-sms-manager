<?php

namespace Bnw\SmsManager;

class SmsResponse
{
    /**
    * The id of the message
    *
    * @var string
    */
    public $messageId;

    /**
    * The text content of the message.
    *
    * @var string
    */
    public $message;

    /**
    * The response status of the message sended
    *
    * @var string
    */
    public $status;

    /**
    * The phone that receveid the message
    *
    * @var string
    */
    public $phone;

    public function messageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    public function status($status)
    {
        $this->status = $status;

        return $this;
    }

    public function phone($phone)
    {
        $this->phone = $phone;

        return $this;
    }
}

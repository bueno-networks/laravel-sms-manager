<?php

namespace Bnw\SmsManager;

class SmsMessage
{
    /**
    * The text content of the message.
    *
    * @var string
    */
    public $message;

    /**
    * @param string $message
    */
    public function __construct($message = '')
    {
        $this->message = $message;
    }
}

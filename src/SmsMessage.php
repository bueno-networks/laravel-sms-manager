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
    * The text content of the message.
    *
    * @var string
    */
    public $recordDb = true;

    /**
    * @param string $message
    */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
    * @param string $message
    */
    public function recordDb(bool $recordDb)
    {
        $this->recordDb = $recordDb;

        return $this;
    }
}

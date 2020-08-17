<?php

namespace bnw\SmsManager\Contracts;

use bnw\SmsManager\SmsMessage;

interface Sms
{
    public __construct(array $config);

    public function sendMessages(array $phones, SmsMessage $message);

    public function sendMessage(String $phone, SmsMessage $message);
}

<?php

namespace Bnw\SmsManager\Contracts;

use Bnw\SmsManager\SmsMessage;

interface Sms
{
    public __construct(array $config);

    public function sendMessages(array $phones, SmsMessage $message);

    public function sendMessage(String $phone, SmsMessage $message);
}

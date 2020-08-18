<?php

namespace Bnw\SmsManager\Contracts;

use Bnw\SmsManager\SmsResponse;
use Bnw\SmsManager\SmsMessage;

interface Sms
{
    public function sendMessages(array $phones, SmsMessage $message) : array;

    public function sendMessage(String $phone, SmsMessage $message) : SmsResponse;
}

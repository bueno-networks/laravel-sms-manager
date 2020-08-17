<?php

namespace Bnw\SmsManager;

use Bnw\SmsManager\Contracts\Sms as SmsContract;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
    * @param \Bnw\SmsManager\Contracts\Sms $client
    */
    public function __construct(SmsContract $client)
    {
        $this->client = $client;
    }

    /**
    * Send the given notification.
    *
    * @param mixed $notifiable
    * @param \Illuminate\Notifications\Notification $notification
    *
    * @return array
    *
    * @throws \NotificationChannels\Discord\Exceptions\CouldNotSendNotification
    */
    public function send($notifiable, Notification $notification)
    {
        if (! $phones = $notifiable->routeNotificationFor('sms')) {
            return;
        }

        $message = $notification->toSMS($notifiable);

        if(is_array($phones)){
            return $this->client->sendMessages($phones, $message);
        }

        return $this->client->sendMessage($phones, $message);
    }
}

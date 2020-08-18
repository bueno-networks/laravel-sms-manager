<?php

namespace Bnw\SmsManager;

use Bnw\SmsManager\Contracts\Sms as SmsContract;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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

        if (is_array($phones) && count($phones) === 0) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (is_array($phones)) {
            $responses = $this->client->sendMessages($phones, $message);

            foreach ($responses as $response) {
                if ($message->recordDb) {
                    $this->createInDatabase($response, $notifiable, $notification->id);
                }
            }
        } else {
            $response = $this->client->sendMessage($phones, $message);

            if ($message->recordDb) {
                $this->createInDatabase($response, $notifiable, $notification->id);
            }
        }
    }

    protected function createInDatabase(SmsResponse $response, $notifiable, $id)
    {
        $model = $notifiable->routeNotificationFor('database');

        if (!Schema::hasTable($model->getRelated()->getTable()) || !$model = $model->find($id)) {
            return;
        }

        $date = now();

        $data = [
            'notification_id'   => $id,
            'driver'            => config('sms-manager.default'),
            'status'            => $response->status,
            'to'                => $response->phone,
            'body'              => $response->message,
            'created_at'        => $date,
            'updated_at'        => $date,
        ];

        if (isset($response->messageId)) {
            $data['id'] = $response->messageId;
        }

        DB::table('sms_messages')->insert($data);
    }
}

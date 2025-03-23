<?php

namespace Rayiumir\Vordia\Http\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Rayiumir\Vordia\Http\Channels\SmsChannel;

class OTPSms extends Notification
{
    use Queueable;

    public $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return [SmsChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [];
    }

    public function toSms($notifiable)
    {
        return [
            'code' => $this->code
        ];
    }
}

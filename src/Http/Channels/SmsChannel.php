<?php

namespace Rayiumir\Vordia\Http\Channels;

use Exception;
use Illuminate\Support\Facades\Log;
use Rayiumir\Vordia\Http\Notifications\OTPSms;

class SmsChannel
{
    public function send($notifiable, OTPSms $OTPSms): string
    {
        $receptor = $notifiable->mobile;
        $parameters = $OTPSms->toSms($notifiable);

        try {
            $driver = app(VordiaManager::class)->driver();
            $driver->send($receptor, $parameters);
            return 'Message sent successfully';
        } catch (Exception $e) {
            Log::error('SMS sending failed', [
                'receptor' => $receptor,
                'error' => $e->getMessage()
            ]);
            return 'Failed to send message: ' . $e->getMessage();
        }
    }
}

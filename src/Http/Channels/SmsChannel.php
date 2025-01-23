<?php

namespace Rayiumir\Vordia\Http\Channels;

use Illuminate\Support\Facades\Log;
use Rayiumir\Vordia\Http\Notifications\OTPSms;

class SmsChannel
{
    public function send($notifiable, OTPSms $OTPSms): string
    {
        $receptor = $notifiable->mobile;
        $param1 = $OTPSms->code;

        $apiKey = env('QaVYy9iOSgKMyF95yMxOVARJre6dakjfmRKFGw697ks1r2iwvzM34rDf6O9kvcdq');
//        $templateId = env('211798');

        if (!$apiKey || !$templateId) {
            Log::error('API key or Template ID is missing');
            return 'Configuration error';
        }

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.sms.ir/v1/send/verify', [
                'mobile' => $receptor,
                'templateId' => '211798',
                'parameters' => [
                    ['name' => 'Code', 'value' => $param1],
                ],
            ]);

            if ($response->successful()) {
                return 'Message sent successfully';
            } else {
                Log::error('SMS sending failed', [
                    'receptor' => $receptor,
                    'status' => $response->status(),
                    'response' => $response->json() ?? $response->body(),
                ]);
                return 'Failed to send message';
            }
        } catch (\Exception $ex) {
            Log::error('SMS sending exception', [
                'receptor' => $receptor,
                'exception' => $ex->getMessage(),
            ]);
            return 'Failed to send message due to an exception';
        }
    }
}

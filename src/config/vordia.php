<?php

return [
    'sms' => [
        /*
        |--------------------------------------------------------------------------
        | Default SMS Driver
        |--------------------------------------------------------------------------
        |
        | This option controls the default SMS driver that is used to send
        | messages to mobile phones.
        |
        | Supported: "smsir"
        |
        */

        'default' => env('SMS_DRIVER', 'smsir'),

        /*
        |--------------------------------------------------------------------------
        | SMS Drivers Configuration
        |--------------------------------------------------------------------------
        |
        | Here you may configure all of the SMS drivers used by your application.
        |
        */

        'drivers' => [
            'smsir' => [
                'class' => Rayiumir\Vordia\Http\Channels\Drivers\Smsir\Smsir::class,
                'config' => [
                    'api_key' => env('SMSIR_API_KEY'),
                    'template_id' => env('SMSIR_OTP_TEMPLATE_ID'),
                ]
            ],
        ]
    ]
];

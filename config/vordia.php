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
        | Supported: "smsir", "Ghasedakme"
        |
        */

        'default' => env('VORDIA_SMS_DRIVER', 'smsir'),

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

            'ghasedak' => [
                'class' => \Rayiumir\Vordia\Http\Channels\Drivers\Ghasedak\Ghasedak::class,
                'config' => [
                    'api_key' => env('Ghasedak_API_KEY'),
                    'template_id' => env('Ghasedak_OTP_TEMPLATE_ID'),
                ]
            ],
        ]
    ]
];

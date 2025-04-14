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
        | Supported: "Smsir", "Ghasedakme", "Melipayamak"
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
                'class' => Rayiumir\Vordia\Http\Channels\Drivers\Ghasedak\Ghasedak::class,
                'config' => [
                    'api_key' => env('GHASEDAK_API_KEY'),
                    'template_id' => env('GHASEDAK_OTP_TEMPLATE_ID'),
                ]
            ],
            'melipayamak' => [
                'class' => Rayiumir\Vordia\Http\Channels\Drivers\Melipayamak\Melipayamak::class,
                'config' => [
                    'username' => env('MELIPAYAMAK_USERNAME'),
                    'password' => env('MELIPAYAMAK_PASSWORD'),
                    'pattern' => env('MELIPAYAMAK_PATTERN'),
                ]
            ],
        ]
    ]
];

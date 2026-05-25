<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

        'firebase' => [
            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS', storage_path(__DIR__.'/google-services.json')),
            ],
            'database_url' => env('FIREBASE_DATABASE_URL', 'https://seismic-vista-462507-f7-default-rtdb.firebaseio.com'),
        ],


        'sms' => [
            'host' => env('SMS_HOST'),
            'user' => env('SMS_USER'),
            'authkey' => env('SMS_AUTHKEY'),
            'sender' => env('SMS_SENDER'),
            'entity_id' => env('SMS_ENTITY_ID'),
            'template_id' => env('SMS_TEMPLATE_ID'),
            'rpt' => env('SMS_RPT', 1),
        ],




];

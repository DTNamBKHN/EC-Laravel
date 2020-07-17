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
    'facebook' => [
        'client_id' => '1191276144559272',  //client face của bạn
        'client_secret' => '963d0de0c856ed6a94460c683712c1d1',  //client app service face của bạn
        'redirect' => 'http://localhost:8888/shopbanhanglaravel/admin/callback' //callback trả về
    ],
    'google' => [
        'client_id' => '638646955536-719f25jb06841vptrlgjbv5coosv8d7r.apps.googleusercontent.com',
        'client_secret' => 'JFq-O-jb_NQFbW11xQFOoxGr',
        'redirect' => 'http://localhost:8888/shopbanhanglaravel/google/callback'
    ],

];

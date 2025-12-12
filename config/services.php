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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ollama AI Configuration
    |--------------------------------------------------------------------------
    */

    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'model'    => env('OLLAMA_MODEL', 'llama3.1:8b'),
    ],

    'n8n' => [
        // Example: http://localhost:5678/webhook-test/send-account
        // For remote/mobile testing, you can point this to an n8n webhook URL exposed via ngrok.
        'send_account_webhook' => env(
            'N8N_SEND_ACCOUNT_WEBHOOK_URL',
            env('N8N_ACCOUNT_WEBHOOK', 'http://localhost:5678/webhook-test/send-account')
        ),
    ],

];

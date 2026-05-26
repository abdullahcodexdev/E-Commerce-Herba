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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | Stripe payment gateway. Keys come from https://dashboard.stripe.com/test/apikeys
    | NEVER hard-code keys — they are read from environment variables only.
    | The secret key is used server-side only; the publishable key is safe for the browser.
    */
    'stripe' => [
        'key'      => env('STRIPE_KEY'),       // pk_test_...  (publishable, frontend)
        'secret'   => env('STRIPE_SECRET'),    // sk_test_...  (secret, backend only)
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],

    /*
    | OpenAI — powers the support chatbot, smart product recommendations and
    | AI-generated product descriptions. The key is server-side ONLY and must
    | never be exposed to the browser. Get a key at https://platform.openai.com.
    */
    'openai' => [
        'key'   => env('OPENAI_API_KEY'),                 // sk-proj-... (secret, backend only)
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),    // fast + low cost; good for chat
    ],

    /*
    | Twilio — SMS (incoming/outgoing) and Voice calls. OPTIONAL & PLACEHOLDER.
    | Leave blank until you buy a Twilio number; the SmsService / VoiceService
    | stay disabled and never break the app. Sign up at https://twilio.com.
    */
    'twilio' => [
        'sid'    => env('TWILIO_SID'),
        'token'  => env('TWILIO_AUTH_TOKEN'),
        'from'   => env('TWILIO_FROM'),         // your Twilio phone number, e.g. +1234567890
    ],

    /*
    | Voice AI agent (e.g. Vapi.ai / Bland.ai) for AI-answered phone calls.
    | OPTIONAL & PLACEHOLDER — leave blank until you set up a provider.
    */
    'voice_ai' => [
        'key'        => env('VOICE_AI_KEY'),
        'agent_id'   => env('VOICE_AI_AGENT_ID'),
        'webhook'    => env('VOICE_AI_WEBHOOK'),
    ],

];

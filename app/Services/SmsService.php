<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SMS sender (Twilio). PLACEHOLDER / ready-to-attach.
 *
 * Stays disabled until TWILIO_SID, TWILIO_AUTH_TOKEN and TWILIO_FROM are set in
 * .env. When you buy a Twilio number and add the keys, send() starts working
 * with no other code changes. Incoming SMS auto-replies can be wired by adding
 * a webhook route that calls AiService->chat() and then SmsService->send().
 */
class SmsService
{
    protected ?string $sid;
    protected ?string $token;
    protected ?string $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.from');
    }

    public function enabled(): bool
    {
        return $this->sid && $this->token && $this->from;
    }

    /** Send an outgoing SMS. Returns true on success, false if disabled/failed. */
    public function send(string $to, string $body): bool
    {
        if (! $this->enabled()) {
            Log::info('SmsService disabled — set Twilio keys in .env to enable.', ['to' => $to]);

            return false;
        }

        try {
            $response = Http::asForm()
                ->withBasicAuth($this->sid, $this->token)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                    'From' => $this->from,
                    'To'   => $to,
                    'Body' => $body,
                ]);

            if ($response->failed()) {
                Log::warning('Twilio SMS failed', ['status' => $response->status(), 'body' => $response->body()]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Twilio SMS threw: '.$e->getMessage());

            return false;
        }
    }
}

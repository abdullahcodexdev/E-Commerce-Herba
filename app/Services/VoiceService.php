<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * AI voice-call agent (Vapi.ai / Bland.ai / Twilio Voice). PLACEHOLDER.
 *
 * Stays disabled until VOICE_AI_KEY is set in .env. This is a scaffold so that
 * when you sign up for a voice provider and add the key, you only implement the
 * provider-specific request inside placeCall() / the incoming webhook — the rest
 * of the app already references this service safely.
 *
 * Incoming calls: point your provider's webhook at a route that uses
 * AiService->chat() to generate replies. Outgoing calls: call placeCall().
 */
class VoiceService
{
    protected ?string $key;
    protected ?string $agentId;

    public function __construct()
    {
        $this->key = config('services.voice_ai.key');
        $this->agentId = config('services.voice_ai.agent_id');
    }

    public function enabled(): bool
    {
        return ! empty($this->key);
    }

    /** Trigger an outgoing AI call. Implement the provider request when you have a key. */
    public function placeCall(string $to, ?string $context = null): bool
    {
        if (! $this->enabled()) {
            Log::info('VoiceService disabled — set VOICE_AI_KEY in .env to enable.', ['to' => $to]);

            return false;
        }

        // TODO (when paid plan is attached): call your provider's API here, e.g. Vapi/Bland.
        Log::info('VoiceService.placeCall called', ['to' => $to, 'context' => $context]);

        return false;
    }
}

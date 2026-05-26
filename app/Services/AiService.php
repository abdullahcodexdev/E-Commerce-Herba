<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around the OpenAI Chat Completions API.
 *
 * The API key lives in config/services.php (env OPENAI_API_KEY) and is used
 * server-side ONLY — it is never sent to the browser. Every public method
 * fails gracefully (returns null / a friendly message) so a missing key or a
 * network hiccup can never take the storefront down.
 */
class AiService
{
    protected ?string $key;
    protected string $model;
    protected string $endpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->key = config('services.openai.key');
        $this->model = config('services.openai.model', 'gpt-4o-mini');
    }

    /** Is the integration configured (key present)? */
    public function enabled(): bool
    {
        return ! empty($this->key);
    }

    /**
     * Send a list of chat messages and return the assistant's reply text.
     *
     * @param  array<int, array{role:string, content:string}>  $messages
     */
    public function chat(array $messages, float $temperature = 0.4, int $maxTokens = 600): ?string
    {
        if (! $this->enabled()) {
            return null;
        }

        try {
            $response = Http::withToken($this->key)
                ->timeout(30)
                ->post($this->endpoint, [
                    'model'       => $this->model,
                    'messages'    => $messages,
                    'temperature' => $temperature,
                    'max_tokens'  => $maxTokens,
                ]);

            if ($response->failed()) {
                Log::warning('OpenAI request failed', [
                    'status' => $response->status(),
                    'body'   => $response->json('error.message') ?? $response->body(),
                ]);

                return null;
            }

            return trim((string) $response->json('choices.0.message.content', ''));
        } catch (\Throwable $e) {
            Log::warning('OpenAI request threw: '.$e->getMessage());

            return null;
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ConvedyaAIService
{
    public function complete(
        array $messages,
        ?string $model = null,
        int $timeout = 30,
        float $temperature = 0.2
    ): string {
        $baseUrl = rtrim((string) config('services.zytro.url'), '/');
        $apiKey = (string) config('services.zytro.api_key');
        $model = $model ?: (string) config('services.zytro.model');

        if ($baseUrl === '' || $apiKey === '' || $model === '') {
            throw new RuntimeException('Zytro API configuration is incomplete.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->connectTimeout(10)
            ->timeout($timeout)
            ->post($baseUrl . '/api/v1/chat/completions', [
                'stream' => false,
                'model' => $model,
                'temperature' => $temperature,
                'messages' => $messages,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Zytro API returned HTTP ' . $response->status() . '.'
            );
        }

        $content = $response->json('choices.0.message.content');

        if (!is_string($content) || trim($content) === '') {
            throw new RuntimeException('Zytro API returned empty or invalid content.');
        }

        return trim($content);
    }
}

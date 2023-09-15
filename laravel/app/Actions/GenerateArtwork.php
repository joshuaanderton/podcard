<?php

declare(strict_types=1);

namespace App\Actions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateArtwork
{
    use AsAction;

    public function handle(string $prompt): string|null
    {
        $apiKey = env('OPENAI_API_KEY');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$apiKey}"
        ])->post('https://api.openai.com/v1/images/generations', [
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024'
        ]);

        if ($response->failed()) {
            throw new Exception($response['error']['message'] ?? 'Failed to generate artwork');
        }

        return $response['data']['url'];
    }

    public function asController(Request $request): JsonResponse
    {
        $request->validate(['prompt' => 'required|string']);
        $response = $this->handle($request->prompt);

        return response()->json(['url' => $response]);
    }
}

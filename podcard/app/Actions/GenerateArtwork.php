<?php

declare(strict_types=1);

namespace App\Actions;

use App\Services\OpenAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateArtwork
{
    use AsAction;

    public function handle(string $prompt): string|null
    {
        return (new OpenAiService)->image($prompt);
    }

    public function asController(Request $request): JsonResponse
    {
        $request->validate(['prompt' => 'required|string']);
        $response = $this->handle($request->prompt);

        return response()->json(['url' => $response]);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use OpenAI;

/**
 * @resource https://github.com/openai-php/client
 */

class OpenAiService
{
    protected $clientObject = null;

    protected function client()
    {
        if ($this->clientObject !== null) {
            return $this->clientObject;
        }

        return $this->clientObject = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function image(string $prompt, ?string $size = "1024x1024"): string
    {
        $sizes = collect(['256x256', '512x512', '1024x1024']);

        if ($sizes->firstWhere(null, $size) === null) {
            $size = $sizes->last();
        }

        $response = $this->client()->images()->create([
            'prompt' => $prompt,
            'size' => $size,
            'n' => 1,
            'response_format' => 'url',
        ]);

        return $response->data[0]->url;
    }

    public function summarize(string $content, int $chars = 200): string
    {
        $response = $this->client()->chat()->create([
            'model' => 'gpt-3.5-turbo-16k',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Summarize content you are provided with into a title that is a maximum of {$chars} characters long.",
                ],
                [
                    'role' => 'user',
                    'content' => $content
                ]
            ]
        ]);

        return $response->choices[0]?->message->content;
    }

    public function transcribe(string $path)
    {
        return $this->client()->audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($path, 'r'),
            'response_format' => 'verbose_json',
        ]);
    }
}

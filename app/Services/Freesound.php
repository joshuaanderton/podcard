<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Freesound
{
    public static function search(?string $term = null): mixed
    {
        return Http::get(
            'https://freesound.org/apiv2/search/text',
            [
                'token' => env('FREESOUND_API_KEY'),
                'query' => $term,
            ]
        )->json();
    }
}

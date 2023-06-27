<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Models\Episode;
use Lorisleiva\Actions\Concerns\AsAction;

class Import
{
    use AsAction;

    public function handle(string $podcastFeedUrl): Episode|null
    {
        $episodes = LoadFeed::run($podcastFeedUrl);

        if (! $episodes || $episodes?->count() === 0) {
            return null;
        }

        $episode = $episodes->sortBy('published_at')->first();

        return Episode::updateOrCreate(['guid' => $episode['guid']], $episode);
    }
}

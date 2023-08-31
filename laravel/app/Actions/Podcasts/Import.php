<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Models\Episode;
use App\Models\PodcastEpisode;
use Lorisleiva\Actions\Concerns\AsAction;

class Import
{
    use AsAction;

    public function handle(string $podcastFeedUrl): PodcastEpisode|null
    {
        $episodes = LoadFeed::run($podcastFeedUrl);

        if (! $episodes || $episodes?->count() === 0) {
            return null;
        }

        $episode = $episodes->sortBy('published_at')->first();

        return PodcastEpisode::updateOrCreate(['guid' => $episode['guid']], $episode);
    }
}

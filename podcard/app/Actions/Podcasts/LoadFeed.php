<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Services\PodcastIndexService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadFeed
{
    use AsAction;

    public function handle(string $feedUrl): array|null
    {
        if (! $podcast = $this->podcast($feedUrl)) {
            return null;
        }

        $episodes = $this->episodes($feedUrl);

        return compact('podcast', 'episodes');
    }

    public function podcast(string $feedUrl): array|null
    {
        $podcast = (new PodcastIndexService)->podcastByFeedUrl($feedUrl);

        if (! $podcast['url'] ?? null) {
            return null;
        }

        return [
            'feed_url' => $podcast['url'],
            'guid' => $podcast['podcastGuid'] ?? null,
            'title' => $podcast['title'],
            'description' => $podcast['description'],
            'link' => $podcast['link'],
            'owner_name' => $podcast['ownerName'],
            'owner_email' => $podcast['ownerEmail'] ?? '',
            'image_url' => $podcast['image'],
        ];
    }

    public function episodes(string $feedUrl): Collection
    {
        $episodes = (new PodcastIndexService)->episodesByFeedUrl($feedUrl);

        return $episodes->reverse()->map(fn ($episode) => [
            'guid' => $episode['guid'],
            'file_url' => $episode['enclosureUrl'],
            'title' => $episode['title'],
            'image_url' => $episode['image'],
            'number' => $episode['episode'],
            'season' => $episode['season'],
            'episode_type' => $episode['episodeType'],
            'published_at' => Carbon::parse($episode['datePublished']),
        ]);
    }
}

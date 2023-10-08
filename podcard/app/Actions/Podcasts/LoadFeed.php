<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Services\PodcastIndexService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadFeed
{
    protected $client;

    public function __construct(string $feedUrl)
    {
        $this->client = new PodcastIndexService($feedUrl);
    }

    public static function run(string $feedUrl): array|null
    {
        $obj = new self($feedUrl);
        $obj->client = new PodcastIndexService($feedUrl);

        if (! $podcast = $obj->podcast()) {
            return null;
        }

        $episodes = $obj->episodes();

        return compact('podcast', 'episodes');
    }

    public function podcast(): array|null
    {
        $podcast = $this->client->podcastByFeedUrl();

        if (! ($podcast['url'] ?? null)) {
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

    public function episodes(): Collection
    {
        $episodes = $this->client->episodesByFeedUrl();

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

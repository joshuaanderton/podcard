<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Services\FeedService;
use App\Services\PodcastIndexService;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadFeed
{
    use AsAction;

    public function handle(string $feedUrl)
    {
        $usePodcastIndexApi = false;

        if ($usePodcastIndexApi) {
            $client = new PodcastIndexService($feedUrl);
            $podcast = $client->podcastByFeedUrl();

            if (! ($podcast['url'] ?? null)) {
                return null;
            }

            $episodes = $client->episodesByFeedUrl();
        } else {
            if (! $feed = FeedService::parse($feedUrl)) {
                return null;
            }

            $podcast = $feed['feed'];
            $episodes = $feed['items'];
        }

        return [
            'podcast' => [
                'feed_url' => $podcast['url'],
                'guid' => $podcast['podcastGuid'] ?? null,
                'title' => $podcast['title'],
                'description' => $podcast['description'],
                'link' => $podcast['link'],
                'owner_name' => $podcast['ownerName'],
                'owner_email' => $podcast['ownerEmail'] ?? '',
                'image_url' => $podcast['image'],
            ],
            'episodes' => $episodes->reverse()->map(fn ($episode) => [
                'guid' => $episode['guid'],
                'file_url' => $episode['enclosureUrl'],
                'title' => $episode['title'],
                'image_url' => $episode['image'],
                'number' => $episode['episode'],
                'season' => $episode['season'],
                'episode_type' => $episode['episodeType'],
                'published_at' => Carbon::parse($episode['datePublished']),
            ])
        ];
    }
}

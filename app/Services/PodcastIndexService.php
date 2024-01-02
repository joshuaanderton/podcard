<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PodcastIndexServiceException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PodcastIndexService
{
    protected string $apiHost = 'https://api.podcastindex.org/api/1.0';

    protected string $apiKey;

    protected string $apiSecret;

    protected string $apiUserAgent;

    protected string $feedUrl;

    protected array $items = [];

    public function __construct(string $feedUrl)
    {
        $this->feedUrl = $this->cleanFeedUrl($feedUrl);
        $this->apiKey = env('PODCAST_INDEX_API_KEY');
        $this->apiSecret = env('PODCAST_INDEX_API_SECRET');
        $this->apiUserAgent = 'Podcard/0.0';

        if (! $this->apiKey || ! $this->apiSecret) {
            throw new Exception('Missing API credentials');
        }
    }

    private function cleanFeedUrl(string $feedUrl)
    {
        $feedUrl = trim($feedUrl);
        $feedUrl = Str::lower($feedUrl);
        $parse = parse_url($feedUrl);
        $feedUrl = implode('', [
            "{$parse['scheme']}://",
            $parse['host'],
            $parse['path']
        ]);

        return $feedUrl;
    }

    public function request(string $endpoint, ?array $params = [], ?bool $throw = true): mixed
    {
        $apiHeaderTime = time();
        $hash = sha1(implode('', [$this->apiKey, $this->apiSecret, $apiHeaderTime]));
        $url = "{$this->apiHost}/{$endpoint}";

        $response = Http::withHeaders([
            'User-Agent' => $this->apiUserAgent,
            'X-Auth-Key' => $this->apiKey,
            'X-Auth-Date' => $apiHeaderTime,
            'Authorization' => $hash,
        ])->get($url, $params);

        if ($response->failed()) {
            if ($throw) {
                throw new Exception($response['description'] ?? 'Unable to make request');
            } else {
                return null;
            }
        }

        return $response->json();
    }

    public function categories(): array|null
    {
        $response = $this->request('categories/list');

        return $response['feeds'] ?? null;
    }

    public function podcastByFeedId(string $id)
    {
        return $this->request('podcasts/byfeedid', ['id' => $id]);
    }

    public function trendingPodcasts()
    {
        $response = $this->request('podcasts/trending');

        return [
            'feeds' => $response['feeds'],
            'count' => $response['count']
        ];
    }

    public function searchPodcasts(string $term)
    {
        $response = $this->request('search/byterm', ['q' => $term]);

        return [
            'feeds' => $response['feeds'],
            'count' => $response['count']
        ];
    }

    public function episodesByFeedUrl(): Collection
    {
        if ($this->items) {
            return collect($this->items);
        }

        try {

            $response = $this->request('episodes/byfeedurl', [
                'url' => $this->feedUrl,
                'max' => 250,
            ]);
            $this->items = $response['items'];

        } catch (Exception $e) {

            // throw new PodcastIndexServiceException($e->getMessage());

            if ($e->getMessage() === 'Feed url not found.') {
                $rssFeed = FeedService::parse($this->feedUrl);
                $this->items = $rssFeed['items']?->toArray() ?? [];

                if ($canonicalFeedUrl = $rssFeed['feed']['url'] ?? null) {
                    $this->addPodcastByFeedUrl($canonicalFeedUrl);
                }
            }

        }

        return collect($this->items);
    }

    public function podcastByFeedUrl(): array|null
    {
        try {

            $response = $this->request('podcasts/byfeedurl', ['url' => $this->feedUrl]);

            if ($response['items'] ?? null) {
                $this->items = $response['items'];
            }

            if ($response['description'] === 'This feed has no meta-data yet.') {
                throw new Exception('Feed url not found.');
            }

            return $response['feed'];

        } catch (Exception $e) {

            // throw new PodcastIndexServiceException($e->getMessage());

            if ($e->getMessage() === 'Feed url not found.') {
                $rssFeed = FeedService::parse($this->feedUrl);
                $this->items = $rssFeed['items']?->toArray() ?? [];

                if ($rssFeed['feed']['url'] ?? null) {
                    $this->addPodcastByFeedUrl();
                    return $rssFeed['feed'];
                }
            }

        }

        return null;
    }

    public function addPodcastByFeedUrl(): bool
    {
        $response = $this->request('add/byfeedurl', ['url' => $this->feedUrl], false);

        return $response?->success() || false;
    }
}

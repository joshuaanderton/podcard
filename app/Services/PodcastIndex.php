<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PodcastIndex
{
    protected string $apiHost = 'https://api.podcastindex.org/api/1.0';

    protected string $apiKey;

    protected string $apiSecret;

    protected string $apiUserAgent;

    public function __construct()
    {
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
            $parse['path'],
        ]);

        return $feedUrl;
    }

    public function request(string $endpoint, array $params)
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
            throw new Exception($response['description'] ?? 'Unable to make request');
        }

        return $response->json();
    }

    public function search(string $term)
    {
        return $this->request('search/byterm', ['q' => $term]);
    }

    public function episodesByFeedUrl(string $feedUrl): Collection
    {
        $feedUrl = $this->cleanFeedUrl($feedUrl);
        $items = null;

        try {

            $response = $this->request('episodes/byfeedurl', ['url' => $feedUrl]);
            $items = $response['items'];

        } catch (Exception $e) {

            if ($e->getMessage() === 'Feed url not found.') {
                $rssFeed = RSS::parse($feedUrl);
                if ($rssFeed['feed']['url'] ?? null) {
                    $this->addPodcastByFeedUrl($feedUrl);
                    $items = $rssFeed['items'];
                }
            }

        }

        return collect($items);
    }

    public function podcastByFeedUrl(string $feedUrl): ?array
    {
        $feedUrl = $this->cleanFeedUrl($feedUrl);

        try {

            $response = $this->request('podcasts/byfeedurl', ['url' => $feedUrl]);

            return $response['feed'];

        } catch (Exception $e) {

            if ($e->getMessage() === 'Feed url not found.') {
                $rssFeed = RSS::parse($feedUrl);
                if ($rssFeed['feed']['url'] ?? null) {
                    $this->addPodcastByFeedUrl($feedUrl);

                    return $rssFeed['feed'];
                }
            }

        }

        return null;
    }

    public function addPodcastByFeedUrl(string $feedUrl): bool
    {
        $feedUrl = $this->cleanFeedUrl($feedUrl);
        $response = $this->request('add/byfeedurl', ['url' => $feedUrl]);

        return $response->success();
    }
}

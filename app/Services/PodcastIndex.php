<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

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
        $feedUrl = rtrim($feedUrl, '/');
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
            throw new Exception('Unable to make request');
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
        $response = $this->request('episodes/byfeedurl', ['url' => $feedUrl]);

        return collect($response['items'] ?? null);
    }

    public function podcastByFeedUrl(string $feedUrl): array|null
    {
        $feedUrl = $this->cleanFeedUrl($feedUrl);
        $response = $this->request('podcasts/byfeedurl', ['url' => $feedUrl]);

        return $response['feed'] ?? null;
    }
}
<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Str;

class RSS
{
    public static function parse(string $feedUrl, ?callable $handleResponse = null)
    {
        //$offset = null;

        // Stream reading of rss feed
        //$context = stream_context_create(['http' => ['follow_location' => false]]);

        try {
            $feed = file_get_contents($feedUrl); //, false, $context, $offset);
        } catch (Exception $e) {
            return null;
        }

        $cleanTags = [
            'itunes:' => '',
            'podcast:' => 'podcast_',
            'atom:' => 'atom_',
        ];

        foreach ($cleanTags as $find => $replace) {
            $feed = Str::replace("<{$find}", "<{$replace}", $feed);
            $feed = Str::replace("</{$find}", "</{$replace}", $feed);
        }

        $feed = simplexml_load_string($feed);

        $feed = $feed->channel;

        $episodes = collect();

        foreach ($feed->item as $episode) {
            if (empty($episode->enclosure['url'])) {
                continue;
            }
            $episodes->push($episode);
        }

        if ($episodes->count() === 0) {
            return null;
        }

        if ($handleResponse) {
            return $handleResponse($episodes);
        }

        return [
            'feed' => [
                'url' => (string) $feed->atom_link['href'] ?? $feedUrl,
                'title' => (string) $feed->title,
                'description' => (string) $feed->description,
                'link' => (string) $feed->link['href'] ?? (string) $feed->link ?? null,
                'ownerName' => (string) $feed->owner->name,
                'ownerEmail' => (string) ($feed->owner->email ?? $feed->podcast_locked['owner']) ?: null,
                'image' => (string) ($feed->image['href'] ?? $feed->image->url ?? '') ?: null,
            ],
            'items' => $episodes->reverse()->map(fn ($episode) => [
                'guid' => (string) $episode->guid,
                'enclosureUrl' => (string) $episode->enclosure['url'],
                'title' => (string) $episode->title,
                'image' => ((string) $episode->image['href'] ?? $episode->image->url) ?: null,
                'episode' => (string) $episode->episode ?? null,
                'season' => (string) $episode->season ?? null,
                'episodeType' => (string) $episode->episodeType,
                'datePublished' => (string) $episode->pubDate,
            ]),
        ];
    }
}

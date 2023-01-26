<?php declare (strict_types=1);

namespace App\Actions\Podcasts;

use App\Models\Podcast;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadFeed
{
    use AsAction;

    public function handle(string $feedUrl): array|null
    {
        $feedUrl = Podcast::cleanFeedUrl($feedUrl);
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

        $episodes = new Collection;

        foreach ($feed->item as $episode) {
            if (empty($episode->enclosure['url'])) {
                continue;
            }
            $episodes->push($episode);
        }

        if ($episodes->count() === 0) {
            return null;
        }

        return [
            'podcast' => [
                'feed_url' => ((string) $feed->atom_link['href']) ?: $feedUrl,
                'title' => $feed->title,
                'description' => $feed->description,
                'link' => ((string) $feed->link['href'] ?? $feed->link) ?: null,
                'owner_name' => $feed->owner->name,
                'owner_email' => (string) ($feed->owner->email ?? $feed->podcast_locked['owner']) ?: null,
                'image_url' => (string) ($feed->image['href'] ?? $feed->image->url ?? '') ?: null,
            ],
            'episodes' => $episodes->reverse()->map(fn ($episode) => [
                'guid' => $episode->guid,
                'file_url' => $episode->enclosure['url'],
                'title' => $episode->title,
                'image_url' => ((string) $episode->image['href'] ?? $episode->image->url) ?: null,
                'number' => $episode->episode ?? null,
                'season' => $episode->season ?? null,
                'episode_type' => $episode->episodeType,
                'published_at' => ($episode->pubDate ?? null) ? Carbon::parse($episode->pubDate) : null,
            ]),
        ];
    }
}

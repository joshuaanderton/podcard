<?php

declare(strict_types=1);

namespace App\Actions\Podcasts;

use App\Models\Podcast;
use Lorisleiva\Actions\Concerns\AsAction;

class ImportFirstOrCreate
{
    use AsAction;

    public function handle(string $feedUrl): Podcast|null
    {
        if (! $feed = LoadFeed::run($feedUrl)) {
            return null;
        }

        $podcastData = $feed['podcast'];
        $episodes = collect($feed['episodes']);

        $podcast = Podcast::firstWhere(['feed_url' => $podcastData['feed_url']]);

        if ($podcast === null) {
            $podcast = Podcast::firstWhere(['feed_url' => $feedUrl]);
        }

        if ($podcast !== null) {
            $podcast->update($podcastData);
        } else {
            $podcast = Podcast::create($podcastData);
        }

        if ($episodes->count() === 0) {
            return $podcast;
        }

        $episodes->each(fn ($episode) => (
            $podcast->episodes()->updateOrCreate(['guid' => $episode['guid']], $episode)
        ));

        return $podcast;
    }
}

<?php

namespace App\Actions\Podcasts;

use App\Models\Podcast;
use Lorisleiva\Actions\Concerns\AsAction;

class Import
{
    use AsAction;

    public function handle(Podcast $podcast): Podcast|null
    {
        if (! $feed = LoadFeed::run($podcast->feed_url)) {
            return null;
        }

        $podcastData = $feed['podcast'];
        $episodes = collect($feed['episodes']);

        $podcast->update($podcastData);

        if ($episodes->count() === 0) {
            return $podcast;
        }

        $episodes->each(fn ($episode) => (
            $podcast->episodes()->updateOrCreate(['guid' => $episode['guid']], $episode)
        ));

        return $podcast;
    }
}

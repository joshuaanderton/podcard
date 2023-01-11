<?php

namespace App\Actions\Podcasts;

use Exception;
use App\Models\Podcast;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
<?php

namespace App\Actions\Podcasts;

use App\Models\Podcast;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class ImportFeed
{
    use AsAction;

    public string $commandSignature = 'podcard:import-feeds';

    public function handle(Podcast $podcast)
    {
        $feed = LoadFeed::run($podcast->feed_url);

        ($feed['episodes'] ?? collect())->each(fn ($episodeData) => (
            $podcast->episodes()->updateOrCreate(
                ['guid' => $episodeData['guid']],
                $episodeData
            )
        ));
    }

    public function asCommand(mixed $command)
    {
        Podcast::where('updated_at', '<', Carbon::now()->subMinutes(30))->get()->map(fn ($podcast) => (
            self::dispatch($podcast)
        ));

        $command->info('Importing feeds');
    }
}

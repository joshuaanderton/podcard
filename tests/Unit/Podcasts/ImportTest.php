<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\Import;
use App\Actions\Podcasts\ImportFirstOrCreate;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_podcast_episode_player_loads_correct_episode()
    {
        $feedUrl = env('APP_URL') . '/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $podcast->update(['title' => 'Test Podcast Title']);

        $firstEpisode = $podcast->episodes()->first();

        $firstEpisode->update(['title' => 'Test Title']);

        $this->assertDatabaseHas(Podcast::class, [
            'title' => 'Test Podcast Title'
        ]);

        $this->assertDatabaseHas(PodcastEpisode::class, [
            'podcast_id' => $podcast->id,
            'title' => 'Test Title',
        ]);

        Import::run($podcast);

        $podcast = $podcast->fresh();
        $firstEpisode = $firstEpisode->fresh();

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);
        
        $this->assertEquals($podcast->title, 'Getting To Ramen');
        $this->assertEquals($firstEpisode->title, "Why I’m making a podcast about SaaS");
    }
}

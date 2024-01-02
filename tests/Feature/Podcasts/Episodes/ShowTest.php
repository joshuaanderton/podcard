<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\ImportFeed;
use App\Actions\Podcasts\LoadFeed;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_podcast_episode_player_loads_correct_episode()
    {
        $feedUrl = 'https://feeds.transistor.fm/ramen';
        $feed = LoadFeed::run($feedUrl);
        ImportFeed::run(
            $podcast = Podcast::create($feed['podcast'])
        );

        $response = $this->get(
            route('podcasts.episodes.show', ['episode' => $podcast->episodes()->first()])
        );

        $response->assertStatus(200);
    }
}

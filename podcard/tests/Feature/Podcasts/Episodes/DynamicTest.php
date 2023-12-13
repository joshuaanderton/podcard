<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\LoadFeed;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicTest extends TestCase
{
    use RefreshDatabase;

    public function test_podcast_episode_dynamic_player_loads_correct_episode_by_title()
    {
        $feedUrl = 'https://feeds.transistor.fm/ramen';
        $feed = LoadFeed::run($feedUrl);
        $episode = $feed['episodes']->first();

        $response = $this->get(
            route('podcasts.episodes.dynamic', [
                'feed' => $feedUrl,
                'episode' => $episode['title'],
            ])
        );

        $response->assertStatus(200);
    }

    public function test_podcast_episode_dynamic_player_loads_correct_episode_by_number()
    {
        $feedUrl = 'https://feeds.transistor.fm/ramen';
        $feed = LoadFeed::run($feedUrl);
        $episode = $feed['episodes']->first();

        $response = $this->get(
            route('podcasts.episodes.dynamic', [
                'feed' => $feedUrl,
                'episode' => $episode['number'],
            ])
        );

        $response->assertStatus(200);
    }
}

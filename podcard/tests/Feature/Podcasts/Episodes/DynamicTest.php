<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\ImportFirstOrCreate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicTest extends TestCase
{
    use RefreshDatabase;

    public function test_podcast_episode_dynamic_player_loads_correct_episode_by_title()
    {
        $feedUrl = env('APP_URL').'/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);
        $episode = $podcast->episodes()->first();

        $response = $this->get(
            route('podcasts.episodes.dynamic', [
                'feed' => $feedUrl,
                'episode' => $episode->title,
            ])
        );

        $response->assertStatus(200);
    }

    public function test_podcast_episode_dynamic_player_loads_correct_episode_by_number()
    {
        $feedUrl = env('APP_URL').'/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);
        $episode = $podcast->episodes()->first();

        $response = $this->get(
            route('podcasts.episodes.dynamic', [
                'feed' => $feedUrl,
                'episode' => $episode->number,
            ])
        );

        $response->assertStatus(200);
    }
}

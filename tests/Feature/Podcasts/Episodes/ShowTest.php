<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\ImportFirstOrCreate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_podcast_episode_player_loads_correct_episode()
    {
        $feedUrl = env('APP_URL').'/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);

        $response = $this->get(
            route('podcasts.episodes.show', ['episode' => $podcast->episodes()->first()])
        );

        $response->assertStatus(200);
    }
}

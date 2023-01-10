<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PlayerBuilder;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PlayerBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_url_pulls_in_episodes()
    {
        $this->assertDatabaseCount(Podcast::class, 0);
        $this->assertDatabaseCount(PodcastEpisode::class, 0);

        $component = Livewire::test(PlayerBuilder::class);

        $component
            ->assertSee('Episode:')
            ->assertSeeHtml('id="iframe"')
            ->set('feedUrl', null)
            ->assertDontSee('Episode:')
            ->assertSeeHtml('id="iframe"');

        $component
            ->assertSee('RSS Feed URL:')
            ->assertDontSee('Episode:')
            ->set('feedUrl', env('APP_URL') . '/tests/ramen.xml')
            ->assertSee('Episode:')
            ->assertSee('Color:');

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $firstEpisode = PodcastEpisode::first();

        $component
            ->set('currentEpisodeId', $firstEpisode->id)
            ->assertSee('id="iframe"');

        $component->assertStatus(200);
    }
}

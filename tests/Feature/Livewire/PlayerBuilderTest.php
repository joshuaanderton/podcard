<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PlayerBuilder;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PlayerBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_url_pulls_in_episodes()
    {
        $feedUrl = env('APP_URL').'/tests/ramen.xml';

        $this->assertDatabaseCount(Podcast::class, 0);
        $this->assertDatabaseCount(PodcastEpisode::class, 0);

        $component = Livewire::test(PlayerBuilder::class, ['feedUrl' => $feedUrl]);

        $component
            ->assertSee('RSS Feed URL:')
            ->set('color', '#000000')
            ->assertSet('color', '#000000');

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $firstEpisode = PodcastEpisode::first();

        $component
            ->assertSet('currentEpisodeId', $firstEpisode->id)
            ->assertSet('currentEpisode', $firstEpisode)
            ->assertSee('Episode:')
            ->assertSee('Player Color:')
            ->assertSeeHtml('id="iframe"');

        $component
            ->set('feedUrl', null)
            ->assertSet('episodes', null)
            ->assertSet('currentEpisodeId', null)
            ->assertSet('currentEpisode', null)
            ->assertSet('color', PodcastEpisode::defaultColor)
            ->assertDontSee('Episode:')
            ->assertDontSee('Player Color:')
            ->assertDontSeeHtml('id="iframe"');
    }
}

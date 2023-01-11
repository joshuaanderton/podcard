<?php

namespace Tests\Feature\Podcasts\Episodes\Show;

use App\Actions\Podcasts\Import;
use App\Actions\Podcasts\ImportFirstOrCreate;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFirstOrCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_import_first_or_create_creates_new_podcast_for_missing_feed_url()
    {
        $feedUrl = env('APP_URL') . '/tests/ramen.xml';

        $this->assertDatabaseCount(Podcast::class, 0);
        $this->assertDatabaseCount(PodcastEpisode::class, 0);

        ImportFirstOrCreate::run($feedUrl);

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $this->assertDatabaseHas(Podcast::class, [
            'title' => 'Getting To Ramen',
            'owner_email' => 'info+ramen@joshanderton.com',
            'owner_name' => 'Josh Anderton',
            'image_url' => 'https://images.transistor.fm/file/transistor/images/show/4088/full_1567571248-artwork.jpg',
            'feed_url' => 'https://feeds.transistor.fm/ramen',
        ]);
    }

    public function test_action_import_first_or_create_updates_existing_podcast_for_existing_canonical_feed_url()
    {
        $feedUrl = env('APP_URL') . '/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);

        $podcast->episodes()->limit(7)->delete();

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 30);

        ImportFirstOrCreate::run($feedUrl);

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $this->assertDatabaseHas(Podcast::class, [
            'title' => 'Getting To Ramen',
            'owner_email' => 'info+ramen@joshanderton.com',
            'owner_name' => 'Josh Anderton',
            'image_url' => 'https://images.transistor.fm/file/transistor/images/show/4088/full_1567571248-artwork.jpg',
            'feed_url' => 'https://feeds.transistor.fm/ramen',
        ]);
    }

    public function test_action_import_first_or_create_updates_existing_podcast_for_existing_provided_feed_url()
    {
        $feedUrl = env('APP_URL') . '/tests/ramen.xml';
        $podcast = ImportFirstOrCreate::run($feedUrl);

        $podcast->update(['feed_url' => $feedUrl]);

        $podcast->episodes()->limit(10)->delete();

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 27);

        ImportFirstOrCreate::run($feedUrl);

        $this->assertDatabaseCount(Podcast::class, 1);
        $this->assertDatabaseCount(PodcastEpisode::class, 37);

        $this->assertDatabaseHas(Podcast::class, [
            'title' => 'Getting To Ramen',
            'owner_email' => 'info+ramen@joshanderton.com',
            'owner_name' => 'Josh Anderton',
            'image_url' => 'https://images.transistor.fm/file/transistor/images/show/4088/full_1567571248-artwork.jpg',
            'feed_url' => 'https://feeds.transistor.fm/ramen',
        ]);
    }
}

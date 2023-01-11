<?php

namespace App\Http\Livewire;

use App\Actions\Podcasts\ImportFirstOrCreate;
use App\Models\PodcastEpisode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Component;

class PlayerBuilder extends Component
{
    public ?Collection $episodes = null;

    public ?PodcastEpisode $currentEpisode = null;

    public ?string $feedUrl = null;

    public ?int $currentEpisodeId = null;

    public ?string $color = null;

    protected $rules = [
        'feedUrl' => 'required|url|max:255',
        'currentEpisodeId' => 'nullable|int',
        'color' => 'nullable|string|max:255',
    ];

    public function updated($name, $value)
    {
        if ($name === 'feedUrl') {
            $this->resetBuilder();

            if ($value) {
                $this->loadFeed();
            }
        }

        if ($name === 'currentEpisodeId' && ($this->currentEpisode->id ?? null) !== $value) {
            $this->currentEpisode = $this->episodes->find($value);
        }

        if ($name === 'color' && ! PodcastEpisode::isColorHex($value)) {
            $this->color = PodcastEpisode::defaultColor;
        }
    }

    public function mount()
    {
        if (! $this->feedUrl) {
            $this->setDemoFeedUrl();
        }
        
        $this->loadFeed();
    }

    public function render()
    {
        return view('livewire.player-builder');
    }

    public function loadFeed(): void
    {
        $podcast = ImportFirstOrCreate::run($this->feedUrl);

        if (! $podcast) {
            $this->feedUrl = null;
            $this->resetBuilder();
            $this->emit('error-message', __('No podcast found'));

            return;
        }

        if ($podcast->episodes()->count() === 0) {
            $this->feedUrl = null;
            $this->resetBuilder();
            $this->emit('error-message', __('No episodes found for podcast'));

            return;
        }

        $this->episodes = $podcast->episodes()->get();
        $this->currentEpisode = $this->episodes->first();
        $this->currentEpisodeId = $this->currentEpisode->id;
    }

    public function setDemoFeedUrl(): void
    {
        $feeds = collect([
            ['feed_url' => 'https://feeds.podhunt.app/feeds/daily/rss',   'color' => '#8772c7'],
            ['feed_url' => 'https://feeds.transistor.fm/founderquest',    'color' => '#b8702d'],
            ['feed_url' => 'https://feeds.transistor.fm/build-your-saas', 'color' => '#fbc85c'],
            ['feed_url' => 'https://feeds.transistor.fm/ramen',           'color' => '#ff4500'],
        ]);

        $randomFeed = $feeds->random();

        $this->feedUrl = $randomFeed['feed_url'];
        $this->color = $randomFeed['color'];
    }

    public function setDemoFeedUrlAndLoad(): void
    {
        $this->setDemoFeedUrl();
        $this->loadFeed();
    }

    public function getPlayerUrlProperty(): string|null
    {
        if (! $episode = $this->currentEpisode) {
            return null;
        }

        $color = Str::remove('#', $this->color);
        $domain = explode('//', config('app.url'))[1];
        $endpoint = "https://player.{$domain}";

        return $endpoint . route('podcasts.episodes.show', compact('episode', 'color'), false);
    }

    public function getPlayerDynamicUrlProperty(): string|null
    {
        if (! $this->currentEpisode) {
            return null;
        }

        return explode('/episodes', $this->playerUrl)[0];
    }

    public function resetBuilder(): void
    {
        $this->episodes = $this->currentEpisode = $this->currentEpisodeId = null;
        $this->color = PodcastEpisode::defaultColor;
    }
}

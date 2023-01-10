<?php

namespace App\Http\Livewire;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class PlayerBuilder extends Component
{
    public ?Collection $episodes = null;

    public ?PodcastEpisode $currentEpisode = null;

    public string $feedUrl;

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

            $this->episodes = $this->currentEpisode = $this->currentEpisodeId = null;
            
            if ($value) {
                $this->loadFeed();
            }
        }
        
        if ($name === 'currentEpisodeId' && ($this->currentEpisode->id ?? null) !== $value) {
            $this->currentEpisode = $this->episodes->find($value);
        }

        if ($name === 'color' && ! $value) {
            $this->color = '#000000';
        }
    }

    public function loadFeed()
    {
        $feedUrl = trim($this->feedUrl);
        
        $podcast = Podcast::firstOrImport($feedUrl);

        if ($podcast->episodes()->count() > 0) {
            $this->episodes = $podcast->episodes()->get();
            $this->currentEpisode = $this->episodes->first();
            $this->currentEpisodeId = $this->currentEpisode->id;
        } else {
            $this->emit('error', __('No episodes found'));
        }
    }

    public function mount()
    {
        $this->setDemo();
    }

    public function render()
    {
        return view('livewire.player-builder');
    }

    public function setDemo(): void
    {
        $feeds = collect([
            ['feed_url' => 'https://feeds.podhunt.app/feeds/daily/rss',   'color' => '#8772c7'],
            ['feed_url' => 'https://feeds.transistor.fm/founderquest',    'color' => '#b8702d'],
            ['feed_url' => 'https://feeds.transistor.fm/build-your-saas', 'color' => '#fbc85c'],
            ['feed_url' => 'https://feeds.transistor.fm/ramen',           'color' => '#ff4500'],
        ]);

        $podcasts = Podcast::whereIn('feed_url', $feeds->pluck('feed_url')->all())->get();

        $randomPodcast = $podcasts->random();
        
        $this->episodes = $randomPodcast->episodes;
        $this->currentEpisode = $this->episodes->first();
        $this->currentEpisodeId = $this->currentEpisode->id;
        $this->feedUrl = $randomPodcast->feed_url;
        $this->color = $feeds->firstWhere('feed_url', $randomPodcast->feed_url)['color'];
    }

    public function getPlayerUrlProperty(): string|null
    {
        if (! $episode = $this->currentEpisode) {
            return null;
        }

        $color = Str::remove('#', $this->color);
        $domain = explode('//', config('app.url'))[1];
        $endpoint = App::environment('local') ? "http://player.{$domain}" : "https://player.{$domain}";

        return "{$endpoint}/episodes/{$episode->id}?color={$color}";
    }
}

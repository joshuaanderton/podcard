<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Actions\Podcasts\LoadFeed;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class PlayerBuilder extends Component
{
    public Collection|null $episodes = null;

    public array|PodcastEpisode|null $previewEpisode = null;

    public string|null $feedUrl = null;

    public string $selectedEpisodeId = 'latest';

    public string|null $color = null;

    protected $rules = [
        'feedUrl' => 'required|url|max:255',
        'selectedEpisodeId' => 'nullable|string',
        'color' => 'nullable|string|max:255',
    ];

    protected $listeners = [
        'success-message' => 'successMessage',
    ];

    public function successMessage(string $message)
    {
        session()->flash('success-message', $message);
    }

    public function updated($name, $value)
    {
        if ($name === 'feedUrl') {
            $this->resetBuilder();

            if ($value) {
                $this->loadFeed();
            }
        }

        if ($name === 'selectedEpisodeId' && ($this->previewEpisode['guid'] ?? null) !== $value) {
            if ($value === 'latest') {
                $this->previewEpisode = $this->episodes->sortBy('published_at')->last();
            } else {
                $this->previewEpisode = $this->episodes->where('guid', $value)->first();
            }
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
        $feed = LoadFeed::run($this->feedUrl);
        $podcastData = $feed['podcast'];
        $episodeDatas = $feed['episodes'];

        if ($podcast = Podcast::whereIn('feed_url', [$this->feedUrl, $podcastData['feed_url'] ?? null])->first()) {
            $this->episodes = $episodeDatas;
            $this->previewEpisode = $podcast->episodes()->latest('published_at')->first() ?: $episodeDatas->sortBy('published_at')->last();
            $this->selectedEpisodeId = 'latest';

            if ($this->previewEpisode !== null) {
                return;
            }
        }

        if (! $episodeDatas || $episodeDatas->count() === 0) {
            $this->feedUrl = null;
            $this->resetBuilder();
            $this->emit('error-message', __('No episodes found for podcast'));

            return;
        }

        $this->episodes = $episodeDatas;
        $this->previewEpisode = $episodeDatas->sortBy('published_at')->last();
        $this->selectedEpisodeId = 'latest';
    }

    public function setDemoFeedUrl(): void
    {
        $feeds = collect([
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
        if ($this->previewEpisode && ! is_array($this->previewEpisode)) {
            return $this->getPlayerDynamicUrlProperty();
        }
        if (! $feed = $this->feedUrl) {
            return null;
        }

        if (! $guid = $this->previewEpisode['guid']) {
            return null;
        }

        $color = Str::remove('#', $this->color);
        $endpoint = config('app.player_url');

        $url = $endpoint.route('podcasts.episodes.dynamic', compact('feed', 'color'), false);

        if ($this->selectedEpisodeId === 'latest') {
            return $url;
        }

        return "{$url}&guid={$guid}";
    }

    public function getPlayerDynamicUrlProperty(): string|null
    {
        if (! $feed = $this->feedUrl) {
            return null;
        }

        if ($this->selectedEpisodeId !== 'latest' && ! $number = $this->previewEpisode['number']) {
            return null;
        }

        $color = Str::remove('#', $this->color);
        $endpoint = config('app.player_url');

        $url = $endpoint.route('podcasts.episodes.dynamic', compact('feed', 'color'), false);

        if ($this->selectedEpisodeId === 'latest') {
            return $url;
        }

        return "{$url}&number={$number}";
    }

    public function resetBuilder(): void
    {
        $this->episodes = $this->previewEpisode = null;
        $this->selectedEpisodeId = 'latest';
        $this->color = PodcastEpisode::defaultColor;
    }
}

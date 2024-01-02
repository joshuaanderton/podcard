<?php

declare(strict_types=1);

namespace App\Actions\Podcasts\Episodes;

use App\Actions\Podcasts\LoadFeed;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Dynamic
{
    protected ?Podcast $podcast = null;

    protected string $feedUrl;

    protected ?array $feed = null;

    public function __invoke(Request $request)
    {
        $request->validate([
            // Feed URL
            'feed' => 'required|string',

            // Lookup
            'episode' => 'nullable',
            'title' => 'nullable|string',
            'number' => 'nullable|string',
            'guid' => 'nullable|string',

            // Options
            'color' => 'nullable|string',
            'border' => 'nullable|numeric',
        ]);

        $this->feedUrl = $request->feed;

        // Find or import podcast by feed URl
        if (! $this->podcastLookup()) {
            return <<<'blade'
                <div style="text-align:center">
                    <h1>Oops!</h1>
                    <p style="color:#999">We couldn't find a podcast for the provided feed URL.</p>
                </div>
            blade;
        }

        // Find or import episode by title/guid/number
        if (! $episode = $this->episodeLookup()) {
            return <<<'blade'
                <div style="text-align:center">
                    <h1>Oops!</h1>
                    <p style="color:#999">We couldn't find an episode for the number or title provider.</p>
                </div>
            blade;
        }

        return view('player', $episode->playerData());
    }

    protected function podcastLookup(): Podcast|null
    {
        if (! $podcast = Podcast::firstWhere('feed_url', $this->feedUrl)) {
            $feed = $this->feed ?: ($this->feed = LoadFeed::run($this->feedUrl));
            $podcastData = $feed['podcast'];

            if ($podcastData['feed_url'] ?? null) {
                $podcast = Podcast::firstOrCreate(['feed_url' => $podcastData['feed_url']], $podcastData);
            }
        }

        return $this->podcast = $podcast;
    }

    protected function episodeLookup(): PodcastEpisode|null
    {
        $episode = null;
        $request = request();
        $podcast = $this->podcast;
        $search = $request->search ?: $request->episode;
        $title = $request->title;
        $guid = $request->guid;
        $number = $request->number;

        if ($title ?: $number ?: $guid) {

            if ($podcast->episodes()->count() > 0) {
                if ($title) {
                    $episode = $podcast->episodes()->where('title', 'LIKE', "%{$title}%")->first();
                } elseif ($guid) {
                    $episode = $podcast->episodes()->where('guid', $guid)->first();
                } elseif ($number) {
                    $episode = $podcast->episodes()->where('number', $number)->first();
                }
            }

            if (! $episode) {
                $feed = $this->feed ?: ($this->feed = LoadFeed::run($this->feedUrl));
                $episodeDatas = $feed['episodes'] ?? collect();

                if ($title) {
                    $episodeData = $episodeDatas->where('title', 'LIKE', "%{$title}%")->first();
                } elseif ($guid) {
                    $episodeData = $episodeDatas->where('guid', $guid)->first();
                } else { //if ($number) {
                    $episodeData = $episodeDatas->where('number', $number)->first();
                }

                if ($episodeData !== null) {
                    $episode = $podcast->episodes()->firstOrCreate(['guid' => $episodeData['guid']], $episodeData);
                }
            }

        } elseif ($search) {

            if ($podcast->episodes()->count() > 0) {
                $episode = $podcast->episodes()->where('guid', $search)->first();
                $episode = $episode ? $episode : $podcast->episodes()->where('number', $search)->first();
                $episode = $episode ? $episode : $podcast->episodes()->where('title', 'LIKE', "%{$search}%")->first();
            }

            if (! $episode) {
                $feed = $this->feed ?: ($this->feed = LoadFeed::run($this->feedUrl));
                $episodeDatas = $feed['episodes'] ?? collect();
                $episodeData = null;

                $episodeData = $episodeDatas->where('guid', $search)->first();
                $episodeData = $episodeData ? $episodeData : $episodeDatas->where('number', $search)->first();
                $episodeData = $episodeData ? $episodeData : $episodeDatas->filter(fn ($ep) => Str::contains($ep['title'], $search))->first();

                if ($episodeData) {
                    $episode = $podcast->episodes()->firstOrCreate(['guid' => $episodeData['guid']], $episodeData);
                }
            }

        } else {

            // Get latest episode
            if ($podcast->episodes()->count() > 0) {
                $episode = $podcast->episodes()->latest('published_at')->first();
            }

            if (! $episode) {
                $feed = $this->feed ?: ($this->feed = LoadFeed::run($this->feedUrl));
                $episodeDatas = $feed['episodes'] ?? collect();

                if ($episodeData = $episodeDatas->first()) {
                    $episode = $podcast->episodes()->firstOrCreate(['guid' => $episodeData['guid']], $episodeData);
                }
            }
        }

        return $episode;
    }
}

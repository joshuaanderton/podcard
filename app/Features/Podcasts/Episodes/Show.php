<?php

namespace App\Features\Podcasts\Episodes;

use App\Models\Podcast;
use Illuminate\Http\Request;

class Show
{
    public function handle(Request $request)
    {
        if (empty($request->feed)) {
            return view('player-builder');
        }

        $request->feed = explode('?', $request->feed)[0];

        $podcast = Podcast::where('feed_url', $request->feed)->first();

        if (! $podcast) {
            $podcast = new Podcast;
            $podcast->feed_url = $request->feed;
        }

        $podcast->import();

        $episode = null;

        // Try to get episode from already imported data
        if (is_numeric($request->episode)) {
            $episode = $podcast->episodes()->where(['number' => $request->episode])->first();
        } elseif (is_string($request->episode)) {
            $episode = $podcast->episodes()->where('title', 'LIKE', "%{$request->episode}%")->first();
        }

        // If no episode is set then let's just get the latest episode
        if (! $episode) {
            $episode = $podcast->episodes()->latest('published_at')->first();
        }

        $color = $request->color ? Podcast::hexToRgb('#'.str_replace('#', '', $request->color)) : false;

        if (! $podcast) {
            return <<<'blade'
        <div style="text-align:center">
          <h1>Oops!</h1>
          <p style="color:#999">Please provide a <strong>?feed=</strong> parameter with your RSS feed url!</p>
        </div>
      blade;
        }

        return view('player', [
            'file_url' => $episode->file_url,
            'cover_url' => $episode->imageUrl(),
            'title' => $episode->title,
            'podcast' => $podcast->title,
            'episode' => $episode->number,
            'season' => $episode->season,
            'border' => $request->border === '0' ? 0 : 1,
            'color' => $color,
            'is_light' => $color && Podcast::isColorLight($color),
            'playerData' => [
                'podcast' => (string) $podcast->title,
                'title' => $episode->title,
                'episode' => $episode->number,
                'cover_url' => $episode->imageUrl(),
                'file_url' => $episode->file_url,
            ],
        ]);
    }
}

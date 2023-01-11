<?php

namespace App\Actions\Podcasts\Episodes;

use App\Actions\Podcasts\ImportFirstOrCreate;
use Illuminate\Http\Request;

class Dynamic
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'feed' => 'required|string',
            'episode' => 'nullable',
            'color' => 'nullable|string',
            'border' => 'nullable|numeric',
        ]);

        if (! $podcast = ImportFirstOrCreate::run($request->feed)) {
            return <<<'blade'
                <div style="text-align:center">
                    <h1>Oops!</h1>
                    <p style="color:#999">Please provide a valid RSS feed url (e.g. https://player.podcast.co/?<strong>feed=</strong>)</p>
                </div>
            blade;
        }

        if (! $request->episode) {
            $episode = $podcast->episodes()->latest('published_at')->first();
        } elseif (is_numeric($request->episode)) {
            $episode = $podcast->episodes()->where(['number' => $request->episode])->first();
        } else {
            $episode = $podcast->episodes()->where('title', 'LIKE', "%{$request->episode}%")->first();
        }

        if (! $episode) {
            return <<<'blade'
                <div style="text-align:center">
                    <h1>Oops!</h1>
                    <p style="color:#999">We couldn't find an episode for the number or title provider.</p>
                </div>
            blade;
        }

        return view('player', $episode->playerData());
    }
}

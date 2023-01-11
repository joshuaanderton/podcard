<?php

namespace App\Actions\Podcasts\Episodes;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\Request;

class Show 
{
    public function __invoke(Request $request, PodcastEpisode $episode)
    {
        $request->validate([
            'color' => 'nullable|string',
            'border' => 'nullable|numeric',
        ]);

        return view('player', $episode->playerData());
    }
}

<?php

namespace App\Features\Podcasts\Episodes;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\Request;

class Show 
{
    public function __invoke(Request $request, PodcastEpisode $episode)
    {
        $color = $request->color ? Podcast::hexToRgb('#' . str_replace('#', '', $request->color)) : false;

        return view('player', [
            'file_url' => $episode->file_url,
            'cover_url' => $episode->imageUrl(),
            'title' => $episode->title,
            'podcast' => $episode->podcast->title,
            'episode' => $episode->number,
            'season' => $episode->season,
            'border' => $request->border === '0' ? 0 : 1,
            'color' => $color,
            'is_light' => $color && Podcast::isColorLight($color),
            'playerData' => [
                'podcast' => (string) $episode->podcast->title,
                'title' => $episode->title,
                'episode' => $episode->number,
                'cover_url' => $episode->imageUrl(),
                'file_url' => $episode->file_url,
            ],
        ]);
    }
}

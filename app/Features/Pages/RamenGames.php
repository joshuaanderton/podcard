<?php

namespace App\Features\Pages;

use App\Models\Podcast;

class RamenGames
{
    public function handle()
    {
        return view('ramen-games', [
            'podcasts' => Podcast::where('ramen_games', true)->paginate(20),
        ]);
    }
}

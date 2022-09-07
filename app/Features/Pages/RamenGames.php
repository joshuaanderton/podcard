<?php

namespace App\Features\Pages;

use App\Models\Podcast;
use Blazervel\Feature\Action;

class RamenGames extends Action
{
    public function handle()
    {
        return view('ramen-games', [
            'podcasts' => Podcast::where('ramen_games', true)->paginate(20),
        ]);
    }
}

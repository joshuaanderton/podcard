<?php

declare(strict_types=1);

namespace App\Actions\Pages;

use App\Models\Podcast;

class RamenGames
{
    public function __invoke()
    {
        return view('ramen-games', [
            'podcasts' => Podcast::where('ramen_games', true)->paginate(20),
        ]);
    }
}

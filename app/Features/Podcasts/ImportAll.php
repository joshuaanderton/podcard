<?php

namespace App\Features\Podcasts;

use App\Models\Podcast;
use Illuminate\Http\Request;
use Blazervel\Feature\Action;

class ImportAll extends Action
{
    public function handle(Request $request)
    {
        $podcast = Podcast::get();

        $podcast->map(function ($p) {
            $p->import();
        });

        return response()->json(['message' => $podcast->count().' feeds updated.'], 202);
    }
}

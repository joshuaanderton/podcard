<?php

namespace App\Features\Podcasts;

use App\Models\Podcast;
use Illuminate\Http\Request;

class ImportAll
{
    public function __invoke(Request $request)
    {
        $podcast = Podcast::get();

        $podcast->map(function ($p) {
            $p->import();
        });

        return response()->json(['message' => $podcast->count().' feeds updated.'], 202);
    }
}

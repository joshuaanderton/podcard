<?php

namespace App\Features\Podcasts;

use App\Models\Podcast;
use Illuminate\Http\Request;

class Import 
{
    public function __invoke(Request $request)
    {
        if (empty($request->feed)) {
            return abort(404);
        }

        $request->feed = explode('?', $request->feed)[0];

        $podcast = Podcast::where('feed_url', $request->feed)->first();

        if (! $podcast || $podcast->episodes()->count() == 0) {
            if (! $podcast) {
                $podcast = new Podcast;
            }
            $podcast->feed_url = $request->feed;
            $podcast->import();
        }

        $podcast->import();

        $podcast->episode_imported = $podcast->episodes()->count();

        return response()->json(['message' => $podcast], 202);
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::group(['domain' => env('SITE_URL')], function() {
    Route::get('/', function(){
        return view('player-builder');
    });
});

Route::domain('player.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(Request $request){

        if (empty($request->feed)) return view('player', ['podcast' => false]);

        $request->feed = explode('?', $request->feed)[0];

        $podcast = \App\Podcast::where('feed_url', $request->feed)->first();

        if (!$podcast || $podcast->episodes()->count() == 0) :
            if (!$podcast) $podcast = new \App\Podcast;
            $podcast->feed_url = $request->feed;
            $podcast->import();
        endif;

        $podcast->import();

        if (is_numeric($request->episode))  $episode = $podcast->episodes()->where(['number' => $request->episode])->first();
        if (is_string($request->episode))   $episode = $podcast->episodes()->where('title', 'LIKE', "%{$request->episode}%")->first();
        if (!$episode && $request->episode) $episode = $podcast->episodes()->offset(intval($request->episode-1))->first();
        if (!$episode)                      $episode = $podcast->episodes()->latest('published_at')->first();

        $color = $request->color ? \App\Podcast::hexToRgb('#' . str_replace('#', '', $request->color)) : false;

        return view('player', [
            'file_url'  => $episode->file_url,
            'cover_url' => $episode->imageUrl(),
            'title'     => $episode->title,
            'podcast'   => $podcast->title,
            'episode'   => $episode->number,
            'season'    => $episode->season,
            'border'    => $request->border === '0' ? 0 : 1,
            'color'     => $color,
            'is_light'  => $color && \App\Podcast::isColorLight($color)
        ]);
    });
    Route::get('/import', function(Request $request){
        if (empty($request->feed)) return abort(404);

        $request->feed = explode('?', $request->feed)[0];

        $podcast = \App\Podcast::where('feed_url', $request->feed)->first();

        if (!$podcast || $podcast->episodes()->count() == 0) :
            if (!$podcast) $podcast = new \App\Podcast;
            $podcast->feed_url = $request->feed;
            $podcast->import();
        endif;

        $podcast->import();

        $podcast->episode_imported = $podcast->episodes()->count();

        return response()->json(['message' => $podcast], 202);
    });
});

Route::domain('{subdomain}.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(string $subdomain){
        $feed = simplexml_load_file('https://anchor.fm/s/d5d3614/podcast/rss');
        // Get card data from $subdomain
        return view('site', [
            'site' => $feed->channel,
            'episodes' => $feed->channel->item,
            'latest' => $feed->channel->item[0]
        ]);
    });
});

Route::group(array('domain' => '{domain}.{tld}'), function(){
    Route::get('/', function(string $domain){
        // Get card data from $domain
        return view('site', []);
    });
});
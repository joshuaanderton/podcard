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
use Illuminate\Support\Facades\Route;

Route::domain('ramengames.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(){
        return redirect(env('SITE_URL') . '/ramengames');
    });
});

Route::group(['domain' => env('SITE_URL')], function() {
    Route::get('/', function(){
        return view('player-builder');
    });

    Route::get('dnt', function(Request $request){
        return view('pages.dnt');
    });
    
    Route::get('/ramengames', function(){
        return view('ramen-games', [
            'podcasts' => \App\Podcast::where('ramen_games', 1)->paginate(20)
        ]);
    });
});

Route::domain('player.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(Request $request){

        if (empty($request->feed)) return view('player-builder');

        $request->feed = explode('?', $request->feed)[0];

        $podcast = \App\Podcast::where('feed_url', $request->feed)->first();

        if (!$podcast) :
            $podcast = new \App\Podcast;
            $podcast->feed_url = $request->feed;
        endif;

        $podcast->import();

        $episode = null;

        // Try to get episode from already imported data
        if (is_numeric($request->episode)) :
            $episode = $podcast->episodes()->where(['number' => $request->episode])->first();
        elseif (is_string($request->episode)) :
            $episode = $podcast->episodes()->where('title', 'LIKE', "%{$request->episode}%")->first();
        endif;

        // If no episode is set then let's just get the latest episode
        if (!$episode) $episode = $podcast->episodes()->latest('published_at')->first();

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

    Route::get('import', function(Request $request){
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

    Route::get('import/all', function(Request $request){
        $podcast = \App\Podcast::get();

        $podcast->map(function($p){
            $p->import();
        });

        return response()->json(['message' => $podcast->count() . ' feeds updated.'], 202);
    });

    Route::get('embed', function(Request $request){

        if (!$request->url) abort(404);

        $parts = parse_url($request->url);
        parse_str($parts['query'], $passed_params);

        foreach($passed_params as $key => $val) :
            if (in_array($key, ['feed', 'color', 'episode'])) :
                $params[$key] = $val;
            endif;
        endforeach;

        $url = 'https://player.' . env('SESSION_DOMAIN') . '?' . http_build_query($params);

        return [
            'version'          => '1.0',
            'provider_name'    => 'Podcard',
            'provider_url'     => env('SITE_URL'),
            'title'            => '',
            'html'             => "<iframe width=\"100%\" height=\"180\" scrolling=\"no\" frameborder=\"0\" src=\"{$url}?as_embed\"></iframe>",
            'height'           => '180',
            'width'            => '800',
            'type'             => 'rich',
            //'thumbnail_url'    => "http://api.screenshotlayer.com/api/capture?access_key=" . env('SCREENSHOT_LAYER_ACCESS_KEY') . "&viewport=1024x612&width=1000&url=" . urlencode($url),
            //'thumbnail_width'  => 1200,
            //'thumbnail_height' => 630
        ];
    });
});

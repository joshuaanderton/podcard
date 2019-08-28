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
        return view('home');
    });
});

Route::domain('player.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(Request $request){
        if (empty($request->feed)) return abort(404);

        $feed   = simplexml_load_file($request->feed);
        $feed   = $feed->channel;
        $latest = $feed->item[0];
        $data   = [
            'file_url'  => $latest->enclosure['url'],
            'cover_url' => $feed->image->url,
            'title'     => $latest->title,
            'podcast'   => $feed->title,
            'episode'   => $latest->episode,
            'border'    => $request->border === '0' ? 0 : 1,
            'color'     => $request->color,
        ];

        return view('player', $data);
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
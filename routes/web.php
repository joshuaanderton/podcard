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
        return response()->json(['message' => 'Nothing here yet.'], 202);
    });
});

Route::domain('player.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(Request $request){
        return view('player', [
            'file_url'  => $request->file,
            'cover_url' => $request->cover,
            'title'     => $request->title,
            'podcast'   => $request->podcast,
            'episode'   => $request->episode,
            /*
            'episode' => [
                'title'          => $request->title,
                'podcast'        => $request->podcast,
                'episode_number' => $request->episode_number,
                'file'           => $request->file,
                'cover'          => $request->cover,
            ]
            */
        ]);
    });
});

Route::domain('{podcast_domain}')->group(function ($router) {
    Route::get('/', function(string $podcast_domain){
        return response()->json(['name' => $podcast_domain], 202);
    });
});

Route::domain('{podcast_subdomain}.' . env('SESSION_DOMAIN'))->group(function ($router) {
    Route::get('/', function(string $podcast_subdomain){
        return response()->json(['name' => $podcast_subdomain], 202);
    });
});

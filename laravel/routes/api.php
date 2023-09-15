<?php

use App\Actions\GenerateArtwork;
use App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', fn (Request $request) => $request->user());
    Route::get('generate/artwork', GenerateArtwork::class)->name('generate.artwork');
});

Route::prefix('v1')->group(function () {
    Route::prefix('podcasts', function () {
        Route::get('search', [V1\PodcastsController::class, 'search']);
        Route::get('trending', [V1\PodcastsController::class, 'trending']);
        Route::get('byfeedid/{id}', [V1\PodcastsController::class, 'showByFeedId']);
    });
});

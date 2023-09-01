<?php

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
});

Route::prefix('v1')->group(function () {
    Route::get('podcasts/search', [V1\PodcastsController::class, 'search']);
    Route::get('podcasts/trending', [V1\PodcastsController::class, 'trending']);
    Route::get('podcasts/byfeedid/{id}', [V1\PodcastsController::class, 'showByFeedId']);
});

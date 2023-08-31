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

use App\Actions\Pages;
use App\Actions\Podcasts;
use App\Http\Livewire;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

if (App::environment(['production', 'staging'])) {
    URL::forceScheme('https');
}

$playerUrl = explode(':', config('app.player_domain'))[0]; // Remove port number in dev

// Player
Route::domain($playerUrl)->group(function () {
    Route::get('/', Podcasts\Episodes\Dynamic::class)->name('podcasts.episodes.dynamic');
    Route::get('episodes/{episode}/{color?}', Podcasts\Episodes\Show::class)->name('podcasts.episodes.show');
    Route::get('embed', Podcasts\Episodes\Embed::class)->name('podcasts.episodes.embed');
});

// Player Builder
Route::get('/', Livewire\PlayerBuilder::class)->name('player.builder');
Route::get('episodes/{episode}/{color?}', Podcasts\Episodes\Show::class)->name('podcasts.episodes.show');

// Ramen Games
Route::get('ramengames', Pages\RamenGames::class);
Route::domain('ramengames.'.config('app.host'))->group(fn () => Route::redirect('/', config('app.url').'/ramengames')); // Redirect old subdomain

// Terms & Policies
Route::get('dnt', fn () => view('pages.dnt'));

Route::get('auth/freesound/callback', fn () => []);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

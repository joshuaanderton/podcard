<?php

use App\Actions\Pages;
use App\Actions\Podcasts;
use App\Http\Livewire;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (App::environment(['production', 'staging'])) {
    URL::forceScheme('https');
}

// Player
Route::domain('player.'.config('app.host'))->group(function ($router) {
    Route::get('/', Podcasts\Episodes\Dynamic::class)->name('podcasts.episodes.dynamic');
    Route::get('embed', Podcasts\Episodes\Embed::class)->name('podcasts.episodes.embed');
    Route::get('episodes/{episode}/{color?}', Podcasts\Episodes\Show::class)->name('podcasts.episodes.show');
});

// Player Builder
Route::get('/', Livewire\PlayerBuilder::class)->name('player.builder');

// Video Builder
Route::get('/video', Pages\VideoBuilder::class)->name('player.video');

// Ramen Games
Route::get('ramengames', Pages\RamenGames::class);
Route::domain('ramengames.'.config('app.host'))->group(fn () => Route::redirect('/', config('app.url').'/ramengames')); // Redirect old subdomain

// Terms & Policies
Route::get('dnt', fn () => view('pages.dnt'));

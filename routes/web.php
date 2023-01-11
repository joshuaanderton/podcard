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

Route::domain('player.'.config('app.host'))->group(function ($router) {
    Route::get('/', Podcasts\Episodes\Dynamic::class)->name('podcasts.episodes.dynamic');
    Route::get('embed', Podcasts\Episodes\Embed::class)->name('podcasts.episodes.embed');
    Route::get('episodes/{episode}/{color?}', Podcasts\Episodes\Show::class)->name('podcasts.episodes.show');
});

Route::domain('ramengames.'.config('app.host'))->group(fn () => (
    Route::redirect('/', config('app.url').'/ramengames')
));

Route::get('/', Livewire\PlayerBuilder::class)->name('player.builder');
Route::get('dnt', fn () => view('pages.dnt'));
Route::get('ramengames', Pages\RamenGames::class);

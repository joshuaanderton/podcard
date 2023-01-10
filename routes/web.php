<?php

use Illuminate\Support\Facades\{ App, URL, Route };
use App\Features\Pages\RamenGames;
use App\Features\Podcasts;

if (App::environment(['production', 'staging'])) :
  URL::forceScheme('https');
endif;

Route::domain('player.' . config('app.host'))->group(function ($router) {
    Route::get('/',                  Podcasts\Episodes\Dynamic::class)->name('podcasts.episodes.dynamic');
    Route::get('episodes/{episode}', Podcasts\Episodes\Show::class)->name('podcasts.episodes.show');
    Route::get('embed',              Podcasts\Episodes\Embed::class)->name('podcasts.episodes.embed');
    Route::get('import',             Podcasts\Import::class)->name('podcasts.import');
    Route::get('import/all',         Podcasts\ImportAll::class)->name('podcasts.import.all');
});

Route::domain('ramengames.' . config('app.host'))->group(fn () => (
    Route::redirect('/', config('app.url') . '/ramengames')
));

Route::get('/',          \App\Http\Livewire\PlayerBuilder::class)->name('player.builder');
Route::get('dnt',        fn () => view('pages.dnt'));
Route::get('ramengames', RamenGames::class);

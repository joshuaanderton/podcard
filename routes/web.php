<?php

use Illuminate\Support\Facades\{ App, URL, Route };
use App\Features\Pages\RamenGames;
use App\Features\Podcasts\{
    Import,
    ImportAll,
    Episodes\Show,
    Episodes\Embed
};

if (App::environment(['production', 'staging'])) :
  URL::forceScheme('https');
endif;

Route::domain('player.' . config('app.host'))->group(function ($router) {
    Route::get('/',          Show::class     )->name('podcasts.episodes.show');
    Route::get('embed',      Embed::class    )->name('podcasts.episodes.embed');
    Route::get('import',     Import::class   )->name('podcasts.import');
    Route::get('import/all', ImportAll::class)->name('podcasts.import.all');
});

Route::domain('ramengames.' . config('app.host'))->group(fn () => (
    Route::redirect('/', config('app.url') . '/ramengames')
));

Route::get('/',          fn () => view('player-builder'));
Route::get('dnt',        fn () => view('pages.dnt'));
Route::get('ramengames', RamenGames::class);

<?php

use Illuminate\Support\Facades\Route;

use App\Features\Podcasts\Episodes\{ Show, Embed };
use App\Features\Podcasts\{ Import, ImportAll };
use App\Features\Pages\RamenGames;

Route::domain(config('app.url'))->group(function() {
    Route::get('/',          fn () => view('player-builder'));
    Route::get('dnt',        fn () => view('pages.dnt'));
    Route::get('ramengames', [RamenGames::class, 'handle']);
});

Route::domain('player.' . config('app.host'))->group(function ($router) {
    Route::get('/',          [Show::class,      'handle'])->name('podcasts.episodes.show');
    Route::get('embed',      [Embed::class,     'handle'])->name('podcasts.episodes.embed');
    Route::get('import',     [Import::class,    'handle'])->name('podcasts.import');
    Route::get('import/all', [ImportAll::class, 'handle'])->name('podcasts.import.all');
});

Route::domain('ramengames.' . config('app.host'))->group(fn () => (
    Route::redirect('/', join('/', [env('SITE_URL'), 'ramengames']))
));

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

Route::domain('player.' . (env('APP_ENV') == 'production' ? 'podcard.co' : 'podcard.test'))->group(function ($router) {
    Route::get('/', function(){
        return view('player');
    });
});

<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('farmers', 'farmers')->name('farmers');
    Route::view('alerts', 'alerts')->name('alerts');
    Route::view('data-sources', 'data-sources')->name('data-sources');
    Route::view('messages', 'messages')->name('messages');
});

require __DIR__.'/settings.php';

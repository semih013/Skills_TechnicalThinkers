<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AlertController;

Route::view('/', 'welcome')->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');

Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
Route::post('/alerts/preview', [AlertController::class, 'preview'])->name('alerts.preview');
Route::post('/alerts/send', [AlertController::class, 'send'])->name('alerts.send');

require __DIR__.'/settings.php';

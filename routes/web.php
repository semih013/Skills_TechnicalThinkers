<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\SmsTestController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts/preview', [AlertController::class, 'preview'])->name('alerts.preview');
    Route::post('/alerts/send', [AlertController::class, 'send'])->name('alerts.send');

    Route::get('/sms-test', [SmsTestController::class, 'index'])->name('sms.test');
    Route::post('/sms-test', [SmsTestController::class, 'send'])->name('sms.test.send');
});

Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');

require __DIR__.'/settings.php';

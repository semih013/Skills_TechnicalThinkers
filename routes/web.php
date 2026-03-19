<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\SmsTestController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsInboxController;
use App\Http\Controllers\SmsRegistrationDemoController;


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/create', [FarmerController::class, 'create'])->name('farmers.create');
    Route::post('/farmers', [FarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [FarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}', [FarmerController::class, 'update'])->name('farmers.update');
    Route::delete('/farmers/{farmer}', [FarmerController::class, 'destroy'])->name('farmers.destroy');

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts/preview', [AlertController::class, 'preview'])->name('alerts.preview');
    Route::post('/alerts/send', [AlertController::class, 'send'])->name('alerts.send');

    Route::get('/sms-test', [SmsTestController::class, 'index'])->name('sms.test');
    Route::post('/sms-test', [SmsTestController::class, 'send'])->name('sms.test.send');


    Route::get('/sms-inbox', [SmsInboxController::class, 'index'])->name('sms.inbox');
    Route::get('/sms-inbox/latest', [SmsInboxController::class, 'latest'])->name('sms.inbox.latest');

    Route::get('/sms-registration-demo', [SmsRegistrationDemoController::class, 'index'])->name('sms.registration.demo');
    Route::post('/sms-registration-demo', [SmsRegistrationDemoController::class, 'store'])->name('sms.registration.demo.store');

});

Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\SupporterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/supporters', [SupporterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('supporters.store');

Route::get('/supporters/check-email', [SupporterController::class, 'checkEmail'])
    ->name('supporters.check-email');

Route::get('/supporters/confirm/{supporter}/{token}', [SupporterController::class, 'confirm'])
    ->name('supporters.confirm');

Route::post('/supporters/resend', [SupporterController::class, 'resend'])
    ->middleware('throttle:3,1')
    ->name('supporters.resend');

Route::post('/supporters/unsubscribe', [SupporterController::class, 'unsubscribeRequest'])
    ->middleware('throttle:3,1')
    ->name('supporters.unsubscribe.request');

Route::get('/supporters/unsubscribe/{supporter}/{token}', [SupporterController::class, 'unsubscribeConfirm'])
    ->name('supporters.unsubscribe.confirm');

<?php

use App\Http\Controllers\Account\AccountDisplayController;
use App\Http\Controllers\Account\FavoriteCostumesDeleteHtmxController;
use App\Http\Controllers\Account\FavoriteCostumesDisplayHtmxController;
use App\Http\Controllers\Account\FavoriteCostumesSubmitHtmxController;
use App\Http\Controllers\Account\NotificationsDisplayHtmxController;
use App\Http\Controllers\Account\NotificationsSubmitHtmxController;
use App\Http\Controllers\Account\ProfileDisplayHtmxController;
use App\Http\Controllers\Account\ProfileSubmitHtmxController;
use App\Http\Controllers\Auth\LoginDisplayController;
use App\Http\Controllers\Auth\LoginSubmitController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterDisplayController;
use App\Http\Controllers\AUth\RegisterHtmxController;
use App\Http\Controllers\Auth\RegisterSubmitController;
use App\Http\Controllers\FaqDisplayController;
use App\Http\Controllers\Widgets\SupportDisplayHtmxController;
use Illuminate\Support\Facades\Route;

Route::get('/faq', FaqDisplayController::class)->name('faq');
Route::get('/support-htmx', SupportDisplayHtmxController::class)->name('support-htmx');

// AUTH
Route::name('auth.')
    ->group(function ()
    {
        Route::get('/login', LoginDisplayController::class)->name('login');
        Route::post('/login', LoginSubmitController::class);
        Route::get('/logout', LogoutController::class)->name('logout');
        Route::get('/register', RegisterDisplayController::class)->name('register');
        Route::post('/register', RegisterSubmitController::class);
        Route::post('/register-htmx/{club}', RegisterHtmxController::class)->name('register-htmx');
    });

//  ACCOUNT
Route::prefix('account')
    ->name('account.')
    ->middleware('auth')
    ->group(function ()
    {
        Route::get('/', AccountDisplayController::class)->name('display');
        Route::get('/profile-htmx', ProfileDisplayHtmxController::class)->name('profile-htmx');
        Route::post('/profile-htmx', ProfileSubmitHtmxController::class);
        Route::post('/profile-htmx', ProfileSubmitHtmxController::class)->name('profile-htmx.submit');
        Route::get('/notifications-htmx', NotificationsDisplayHtmxController::class)->name('notifications-htmx');
        Route::post('/notifications-htmx', NotificationsSubmitHtmxController::class);
        Route::get('/favorite-costumes-htmx', FavoriteCostumesDisplayHtmxController::class)->name('favorite-costumes-htmx');
        Route::post('/favorite-costumes-htmx', FavoriteCostumesSubmitHtmxController::class);
        Route::delete('/favorite-costumes-htmx', FavoriteCostumesDeleteHtmxController::class);
    });
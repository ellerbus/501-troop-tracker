<?php

use App\Http\Controllers\Account\AccountDisplayController;
use App\Http\Controllers\Account\NotificationsDisplayHtmxController;
use App\Http\Controllers\Account\NotificationsSubmitHtmxController;
use App\Http\Controllers\Account\ProfileSubmitHtmxController;
use App\Http\Controllers\Account\TrooperCostumesDeleteHtmxController;
use App\Http\Controllers\Account\TrooperCostumesDisplayHtmxController;
use App\Http\Controllers\Account\TrooperCostumesSubmitHtmxController;
use App\Http\Controllers\Admin\AdminDisplayController;
use App\Http\Controllers\Admin\Awards\AwardDisplayController;
use App\Http\Controllers\Admin\SettingsDisplayController;
use App\Http\Controllers\Admin\SettingsSubmitController;
use App\Http\Controllers\Admin\Troopers\TrooperApprovalDisplayController;
use App\Http\Controllers\Admin\Troopers\TrooperApprovalSubmitHtmxController;
use App\Http\Controllers\Admin\Troopers\TrooperDenialSubmitHtmxController;
use App\Http\Controllers\Admin\Troopers\TroopersDisplayController;
use App\Http\Controllers\Admin\Troopers\TrooperProfileDisplayController;
use App\Http\Controllers\Admin\Troopers\TrooperProfileSubmitHtmxController;
use App\Http\Controllers\Auth\LoginDisplayController;
use App\Http\Controllers\Auth\LoginSubmitController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterDisplayController;
use App\Http\Controllers\Auth\RegisterHtmxController;
use App\Http\Controllers\Auth\RegisterSubmitController;
use App\Http\Controllers\Dashboard\DashboardDisplayController;
use App\Http\Controllers\Dashboard\HistoricalTroopsHtmxController;
use App\Http\Controllers\Dashboard\TaggedUploadsHtmxController;
use App\Http\Controllers\Dashboard\TrooperAwardsHtmxController;
use App\Http\Controllers\Dashboard\TrooperDonationsHtmxController;
use App\Http\Controllers\Dashboard\UpcomingTroopsHtmxController;
use App\Http\Controllers\FaqDisplayController;
use App\Http\Controllers\Search\CostumeSearchController;
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
        Route::post('/register-htmx/{organization}', RegisterHtmxController::class)->name('register-htmx');
    });

//  ACCOUNT
Route::prefix('account')
    ->name('account.')
    ->middleware('auth')
    ->group(function ()
    {
        Route::get('/', AccountDisplayController::class)->name('display');
        Route::post('/profile-htmx', ProfileSubmitHtmxController::class)->name('profile-htmx');
        Route::get('/notifications-htmx', NotificationsDisplayHtmxController::class)->name('notifications-htmx');
        Route::post('/notifications-htmx', NotificationsSubmitHtmxController::class);
        Route::get('/trooper-costumes-htmx', TrooperCostumesDisplayHtmxController::class)->name('trooper-costumes-htmx');
        Route::post('/trooper-costumes-htmx', TrooperCostumesSubmitHtmxController::class);
        Route::delete('/trooper-costumes-htmx', TrooperCostumesDeleteHtmxController::class);
    });

//  DASHBOARD
Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware('auth')
    ->group(function ()
    {
        Route::get('/', DashboardDisplayController::class)->name('display');
        Route::get('/upcoming-troops-htmx', UpcomingTroopsHtmxController::class)->name('upcoming-troops-htmx');
        Route::get('/historical-troops-htmx', HistoricalTroopsHtmxController::class)->name('historical-troops-htmx');
        Route::get('/donations-htmx', TrooperDonationsHtmxController::class)->name('donations-htmx');
        Route::get('/awards-htmx', TrooperAwardsHtmxController::class)->name('awards-htmx');
        Route::get('/tagged-uploads-htmx', TaggedUploadsHtmxController::class)->name('tagged-uploads-htmx');
    });

//  ADMIN
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'check.role:moderator,admin'])
    ->group(function ()
    {
        Route::get('/', AdminDisplayController::class)->name('display');
        Route::get('/settings', SettingsDisplayController::class)->name('settings');
        Route::post('/settings/{setting}', SettingsSubmitController::class)->name('settings-htmx');

        //  ADMIN/AWARDS
        Route::prefix('awards')->name('awards.')->group(function ()
        {
            Route::get('/', AwardDisplayController::class)->name('display');
        });

        //  ADMIN/TROOPERS
        Route::prefix('troopers')->name('troopers.')->group(function ()
        {
            Route::get('/display', TroopersDisplayController::class)->name('display');
            Route::get('/trooper/{trooper}', TrooperProfileDisplayController::class)->name('trooper');
            Route::post('/trooper/{trooper}/profile', TrooperProfileSubmitHtmxController::class)->name('profile-htmx');
            Route::get('/approvals', TrooperApprovalDisplayController::class)->name('approvals');
            Route::post('/approvals/{trooper}/approve', TrooperApprovalSubmitHtmxController::class)->name('approve-htmx');
            Route::post('/approvals/{trooper}/deny', TrooperDenialSubmitHtmxController::class)->name('deny-htmx');
        });
    });

//  SEARCH
Route::prefix('search')
    ->name('search.')
    ->group(function ()
    {
        Route::get('/costumes/{organization}', CostumeSearchController::class)->name('costumes');
    });
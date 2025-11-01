<?php

use App\Http\Controllers\AUth\RegisterHtmxController;
use App\Http\Controllers\Auth\RegisterSubmitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqDisplayController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterDisplayController;
use App\Http\Controllers\Auth\LoginSubmitController;
use App\Http\Controllers\Auth\LoginDisplayController;

Route::get('/faq', FaqDisplayController::class)->name('faq');
Route::get('/login', LoginDisplayController::class)->name('login');
Route::post('/login', LoginSubmitController::class);
Route::get('/logout', LogoutController::class)->name('logout');
Route::get('/register', RegisterDisplayController::class)->name('register');
Route::post('/register', RegisterSubmitController::class);
Route::post('/register-htmx/{club}', RegisterHtmxController::class)->name('register-htmx');
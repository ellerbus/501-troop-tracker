<?php

use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSubmitController;
use App\Http\Controllers\LoginDisplayController;
use App\Http\Controllers\FaqDisplayController;

Route::get('/faq', FaqDisplayController::class)->name('faq');
Route::get('/login', LoginDisplayController::class)->name('login');
Route::post('/login', LoginSubmitController::class);
Route::get('/logout', LogoutController::class)->name('logout');
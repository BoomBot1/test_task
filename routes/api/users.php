<?php

use App\Http\Controllers\Api\v1\Users\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/profile', 'show');
    });

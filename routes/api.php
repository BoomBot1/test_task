<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
        require __DIR__ . '/api/auth.php';
        require __DIR__ . '/api/users.php';
    });

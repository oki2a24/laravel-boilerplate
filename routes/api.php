<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('v1')
    ->name('api.v1.')
    ->group(base_path('routes/api/v1.php'));

// Future versions can be added here easily:
// Route::middleware('api')->prefix('v2')->name('api.v2.')->group(base_path('routes/api/v2.php'));

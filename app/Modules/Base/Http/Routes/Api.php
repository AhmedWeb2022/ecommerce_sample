<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Base\Http\Controllers\Base\BaseController;

Route::prefix('api')->group(function () {

    Route::controller(BaseController::class)->group(function () {
        Route::get('fetchBase', 'fetchBase');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Base\Http\Controllers\Base\BaseController;
use App\Modules\Category\Http\Controllers\Api\Brand\BrandController;
use App\Modules\Category\Http\Controllers\Api\Category\CategoryController;

Route::prefix('api')->group(function () {

    Route::controller(CategoryController::class)->group(function () {
        Route::post('fetch_categories', 'fetchCategories');
        Route::post('fetch_category_details', 'fetchCategoryDetails');
    });
});

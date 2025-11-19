<?php

use App\Modules\Category\Http\Controllers\Dashboard\Brand\BrandController;
use App\Modules\Category\Http\Controllers\Dashboard\Category\CategoryController;
use App\Modules\Category\Http\Controllers\Dashboard\Category\CategoryProductController;
use App\Modules\Category\Http\Controllers\Dashboard\Collection\CollectionController;
use App\Modules\Category\Http\Controllers\Dashboard\Label\LabelController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->group(function () {
    Route::middleware('baseAuthMiddleware:employee')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::post('fetch_categories', 'fetchCategories');
            Route::post('fetch_category_details', 'fetchCategoryDetails');
            Route::post('create_category', 'createCategory');
            Route::post('update_category', 'updateCategory');
            Route::post('delete_category', 'deleteCategory');
        });
    });
});

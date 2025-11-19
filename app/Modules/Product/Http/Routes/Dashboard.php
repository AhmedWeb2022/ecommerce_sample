<?php

use App\Modules\Product\Http\Controllers\Dashboard\Product\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->group(function () {
    Route::middleware('baseAuthMiddleware:employee')->group(function () {

        Route::controller(ProductController::class)->group(function () {
            Route::post('fetch_products', 'fetchProducts');
            Route::post('fetch_product_details', 'fetchProductDetails');
            Route::post('create_product', 'createProduct');
            Route::post('update_product', 'updateProduct');
            Route::post('delete_product', 'deleteProduct');
        });
    });
});

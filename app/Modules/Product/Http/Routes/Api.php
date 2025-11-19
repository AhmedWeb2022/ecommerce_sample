<?php

use App\Modules\Product\Http\Controllers\Api\Cart\CartController;
use App\Modules\Product\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('api')->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::post('fetch_products', 'fetchProducts');
        Route::post("fetch_product_details", 'fetchProductDetails');
    });
    Route::group(['middleware' => 'baseAuthMiddleware:user'], function () {
        Route::controller(CartController::class)->group(function () {
            Route::post('fetch_cart_products', 'fetchCarts');
            Route::post('fetch_cart_details', 'fetchCartDetails');
            Route::post('add_cart_product', 'createCart');
            Route::post('update_cart_product', 'updateCart');
            Route::post('delete_cart_product', 'deleteCart');
            Route::get('confirm_cart', 'confirmCart');
        });
    });
});
